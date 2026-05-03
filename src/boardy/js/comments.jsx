const { useState, useEffect } = React;

const API = 'https://boardy-api.cultuly.ai-info.ru';
const POST_ID = 1;

// === НОВОЕ: компонент-обёртка, управляющий JWT и передающий его вниз ===

function App() {
    // --- состояние для JWT ---
    const [jwt, setJwt] = useState(null);

    // --- получение JWT при монтировании ---
    useEffect(() => {
        fetch('/api/me.php', { credentials: 'include' })
            .then(r => {
                if (!r.ok) return null;        // не залогинен → вернём null
                return r.json();
            })
            .then(data => {
                if (data && data.token){
		    setJwt(data.token);
		    console.log('JWT:', data.token);
		}
                else setJwt(null);
            })
            .catch(() => setJwt(null));
    }, []);

    const [reloadKey, setReloadKey] = useState(0);
    const forceReload = () => setReloadKey(k => k + 1);

    return (
        <div className="container py-4">
            <h1 className="mb-4">💬 Комментарии к посту #{POST_ID}</h1>
            
            {/* === ИЗМЕНЕНИЕ: передаём jwt в форму, она решает показывать ли себя === */}
            <CommentForm postId={POST_ID} onAdded={forceReload} jwt={jwt} />
            
            <hr />
            
            {/* === ИЗМЕНЕНИЕ: передаём jwt в список, чтобы дальше в CommentCard === */}
            <CommentList key={reloadKey} jwt={jwt} />
        </div>
    );
}

// ================= CommentList =================

function CommentList({ jwt }) {   // *** ИЗМЕНЕНИЕ: добавлен пропс jwt ***
    const [items, setItems] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const load = async () => {
        try {
            setLoading(true);
            const res = await fetch(`${API}/api/posts/${POST_ID}/comments`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();
            setItems(data.items || []);
            setError(null);
        } catch (err) {
            console.error('Ошибка загрузки:', err);
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => { load(); }, []);

    const formatDate = (iso) => {
        if (!iso) return '';
        return new Date(iso).toLocaleString('ru-RU', {
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    };

    if (loading) return <div className="text-muted">Загрузка...</div>;
    if (error) return <div className="text-danger">Ошибка: {error}</div>;
    if (!items.length) return <div className="text-muted">Комментариев пока нет</div>;

    return (
        <div className="comments-list">
            {items.map(item => (
                <CommentCard 
                    key={item.id} 
                    item={item} 
                    formatDate={formatDate}
                    onUpdated={load}
                    jwt={jwt}   // === НОВОЕ: прокидываем jwt в карточку ===
                />
            ))}
        </div>
    );
}

// ================= CommentCard =================

function CommentCard({ item, formatDate, onUpdated, jwt }) {   // *** ИЗМЕНЕНИЕ: +jwt ***
    const [editMode, setEditMode] = useState(false);
    const [editText, setEditText] = useState(item.body);

    // === НОВОЕ: вспомогательная функция для заголовков ===
    const authHeaders = () => {
        const headers = { 'Content-Type': 'application/json' };
        if (jwt) {
            headers['Authorization'] = 'Bearer ' + jwt;
        }
        return headers;
    };

    const handleSave = async () => {
        if (!editText.trim()) return;
        try {
            const res = await fetch(`${API}/api/comments/${item.id}`, {
                method: 'PUT',
                headers: authHeaders(),   // === ИЗМЕНЕНИЕ: теперь с Bearer ===
                body: JSON.stringify({body: editText})
            });
            if (!res.ok) throw new Error('Не удалось сохранить');
            setEditMode(false);
            onUpdated();
        } catch (err) {
            alert('Ошибка: ' + err.message);
        }
    };

    const handleDelete = async () => {
        if (!confirm('Удалить комментарий?')) return;
        try {
            const res = await fetch(`${API}/api/comments/${item.id}`, {
                method: 'DELETE',
                headers: authHeaders()   // === ИЗМЕНЕНИЕ: теперь с Bearer ===
            });
            if (!res.ok) throw new Error('Не удалось удалить');
            onUpdated();
        } catch (err) {
            alert('Ошибка: ' + err.message);
        }
    };

    // === НОВОЕ: прячем кнопки редактирования/удаления, если нет токена ===
    const showActions = !!jwt;

    return (
        <article className="card mb-3">
            <div className="card-body">
                <div className="d-flex justify-content-between">
                    <strong className="card-title">
                        {item.author_name || 'Аноним'}
                    </strong>
                    <small className="text-muted">
                        {formatDate(item.created_at)}
                    </small>
                </div>
                
                {editMode ? (
                    <div className="mt-2">
                        <textarea 
                            className="form-control mb-2"
                            value={editText}
                            onChange={(e) => setEditText(e.target.value)}
                            rows="3"
                        />
                        <div className="btn-group">
                            <button className="btn btn-success btn-sm" onClick={handleSave}>
                                💾 Сохранить
                            </button>
                            <button className="btn btn-secondary btn-sm" onClick={() => setEditMode(false)}>
                                ✕ Отмена
                            </button>
                        </div>
                    </div>
                ) : (
                    <>
                        <p className="card-text mt-2">{item.body}</p>
                        {/* === ИЗМЕНЕНИЕ: кнопки только если есть токен === */}
                        {showActions && (
                            <div className="btn-group">
                                <button 
                                    className="btn btn-outline-secondary btn-sm"
                                    onClick={() => { setEditMode(true); setEditText(item.body); }}
                                >
                                    ✏️
                                </button>
                                <button 
                                    className="btn btn-outline-danger btn-sm"
                                    onClick={handleDelete}
                                >
                                    🗑️
                                </button>
                            </div>
                        )}
                    </>
                )}
            </div>
        </article>
    );
}

// ================= CommentForm =================

function CommentForm({ postId, onAdded, jwt }) {   // *** ИЗМЕНЕНИЕ: +jwt ***
    const [text, setText] = useState('');
    const [sending, setSending] = useState(false);

    // === НОВОЕ: если токена нет, форму вообще не показываем ===
    if (!jwt) {
        return (
            <div className="alert alert-info">
                🔒 <a href="/login.php" className="alert-link">Войдите</a>, чтобы оставить комментарий.
            </div>
        );
    }

    const handleSubmit = async (e) => {
        e?.preventDefault();
        if (!text.trim() || sending) return;
        
        try {
            setSending(true);
            const headers = { 'Content-Type': 'application/json' };
            // === ИЗМЕНЕНИЕ: добавляем Bearer-токен ===
            if (jwt) {
                headers['Authorization'] = 'Bearer ' + jwt;
            }

            const res = await fetch(`${API}/api/posts/${postId}/comments`, {
                method: 'POST',
                headers,                       
                body: JSON.stringify({body: text})
            });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(err.detail || `HTTP ${res.status}`);
            }
            setText('');
            onAdded();   
        } catch (err) {
            console.error('Ошибка отправки:', err);
            alert('Не удалось отправить: ' + err.message);
        } finally {
            setSending(false);
        }
    };

    return (
        <form onSubmit={handleSubmit} className="input-group mb-4">
            <input 
                className="form-control" 
                placeholder="Ваш комментарий..."
                value={text}
                onChange={(e) => setText(e.target.value)}
                disabled={sending}
            />
            <button 
                className="btn btn-primary" 
                type="submit"
                disabled={sending || !text.trim()}
            >
                {sending ? 'Отправка...' : 'Отправить'}
            </button>
        </form>
    );
}

// ================= Точка входа =================

const root = ReactDOM.createRoot(document.getElementById('app'));
root.render(<App />);
