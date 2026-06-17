@extends('layouts.app')

@section('title', 'Посты')

@section('content')

<h1>Все посты</h1>

{{-- 1. Добавляем обертку с id="posts-feed", которая нужна для JS --}}
<div id="posts-feed">
    @forelse ($posts as $post)

        {{-- 2. Объединяем стили: добавили class="card" из JS-примера --}}
        <article class="card">
            <h2>
                <a href="{{ route('posts.show', $post) }}">
                    {{ $post->title }}
                </a>
            </h2>

            <p>{{ $post->body }}</p>

            <small>
                Автор: {{ $post->author->name }}
                ·
                {{ $post->created_at->format('d.m.Y H:i') }}
            </small>
        </article>

        <hr>

    @empty

        {{-- Добавили id, чтобы JS мог легко удалить эту надпись, когда прилетит первый реальный пост --}}
        <p id="no-posts">Постов пока нет.</p>

    @endforelse
</div>

{{ $posts->links() }}

@endsection
@push('scripts')
<script> 
@if(app()->environment('production')) 
const wsUrl = 'wss://{{ config("app.fastapi_domain") }}/ws' 
@else 
const wsUrl = 'ws://localhost:8000/ws' 
@endif 
 
function connect() { 
    const ws = new WebSocket(wsUrl) 
    ws.onopen    = () => console.log('WS connected') 
    ws.onmessage = (e) => {
        console.log("WebSocket событие:", JSON.parse(e.data)); 
        const msg = JSON.parse(e.data) 
        if (msg.type === 'new_post') prependPost(msg.post) 
    } 
    ws.onclose = () => setTimeout(connect, 3000) 
} 
 
function prependPost(post) {
    const feed = document.getElementById('posts-feed');
    if (!feed) return;

    const noPostsMessage = document.getElementById('no-posts');
    if (noPostsMessage) noPostsMessage.remove();

    const el = document.createElement('article');
    el.className = 'card';
    
    const postUrl = "https://boardy.cultuly.ai-info.ru/posts/:id".replace(':id', post.id);

    // Заменяем post.body на post.content (как в базе данных Laravel)
    // И подставляем имя автора (в зависимости от того, как вы передаете его из Laravel. 
    // Если Laravel передает связь, это может быть post.user.name, если нет — используйте post.user_id или подпорку)
    const postContent = post.content || post.body || '';
    const postAuthor = (post.user && post.user.name) || post.author || 'Загрузка...';

    el.innerHTML = `
        <h2>
            <a href="${postUrl}">${escapeHtml(post.title)}</a>
        </h2>
        <p>${escapeHtml(postContent)}</p>
        <small>Автор: ${escapeHtml(postAuthor)} · Только что</small>
    `;

    // Создаем элемент разделителя, чтобы верстка не ломалась
    const hr = document.createElement('hr');

    // Добавляем сначала карточку, а затем разделитель в начало ленты
    feed.prepend(hr);
    feed.prepend(el);
}
 
function escapeHtml(str) { 
    const d = document.createElement('div') 
    d.textContent = str 
    return d.innerHTML 
} 
 
connect() 
</script> 
@endpush
