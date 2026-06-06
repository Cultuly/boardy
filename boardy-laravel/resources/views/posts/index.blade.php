@extends('layouts.app')

@section('title', 'Все посты')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h1>Все посты</h1>
        @auth
            <a href="{{ route('posts.create') }}" id="create-post-btn"
               style="padding: 0.5rem 1rem; background: #10b981; color: white; text-decoration: none; border-radius: 0.25rem; font-weight: 500;">
                Создать пост
            </a>
        @endauth
    </div>

    @auth
        {{-- Кнопка показывается только если в sessionStorage ещё нет access_token --}}
        <div id="oauth-login-box" style="display: none; margin-bottom: 1rem; padding: 0.75rem 1rem; background: #f1f5f9; border-radius: 0.25rem;">
            Чтобы оставлять комментарии — получите OAuth-токен (PKCE flow):
            <button id="login-btn"
                    style="margin-left: 0.5rem; padding: 0.4rem 0.9rem; background: #3b82f6; color: white;
                           border: none; border-radius: 0.25rem; cursor: pointer; font-weight: 500;">
                Войти через OAuth
            </button>
        </div>
    @endauth

    <div id="posts-feed">
        @forelse ($posts as $post)
            <article>
                <h3>
                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                </h3>
                <p>{{ Str::limit($post->body, 200) }}</p>
                <small>
                    Автор: {{ $post->author->name }} ·
                    {{ $post->created_at->format('d.m.Y H:i') }}
                </small>
            </article>
        @empty
            <p class="text-center text-gray-500">Постов пока нет.</p>
        @endforelse
    </div>

    @if ($posts->hasPages())
        <div style="margin-top:1rem;">
            {{ $posts->links() }}
        </div>
    @endif

    {{-- PKCE login: только для залогиненных в Laravel, и только если ещё нет OAuth-токена --}}
    @auth
        @vite(['resources/js/auth.js'])
    @endauth

    {{-- WebSocket: посты в реалтайме видны всем (и гостям, и авторизованным) --}}
    <script>
        (function () {
            const wsUrl = 'ws://localhost/ws';
            let ws = null;

            function connect() {
                ws = new WebSocket(wsUrl);

                ws.onopen = () => console.log('WS connected');

                ws.onmessage = (event) => {
                    const msg = JSON.parse(event.data);
                    if (msg.type === 'new_post') {
                        prependPost(msg.post);
                    } else if (msg.type === 'post_updated') {
                        updatePost(msg.post);
                    } else if (msg.type === 'post_deleted') {
                        removePost(msg.post_id);
                    }
                };

                ws.onclose = () => {
                    console.log('WS closed, reconnecting in 3s...');
                    setTimeout(connect, 3000);
                };

                ws.onerror = (e) => console.error('WS error', e);
            }

            function prependPost(post) {
                const feed = document.getElementById('posts-feed');
                if (!feed) return;

                const article = document.createElement('article');
                article.innerHTML = `
                    <h3><a href="/posts/${post.id}">${escapeHtml(post.title)}</a></h3>
                    <p>${escapeHtml(post.body)}</p>
                    <small>
                        Автор: ${escapeHtml(post.author)} ·
                        только что
                    </small>
                `;
                feed.prepend(article);
            }

            function updatePost(post) {
                const feed = document.getElementById('posts-feed');
                if (!feed) return;
                const link = feed.querySelector(`a[href="/posts/${post.id}"]`);
                if (link) {
                    link.textContent = post.title;
                    const article = link.closest('article');
                    if (article) {
                        const p = article.querySelector('p');
                        if (p) p.textContent = post.body.substring(0, 200);
                    }
                }
            }

            function removePost(postId) {
                const feed = document.getElementById('posts-feed');
                if (!feed) return;
                const link = feed.querySelector(`a[href="/posts/${postId}"]`);
                if (link) {
                    const article = link.closest('article');
                    if (article) article.remove();
                }
            }

            function escapeHtml(str) {
                const d = document.createElement('div');
                d.textContent = str;
                return d.innerHTML;
            }

            connect();
        })();
    </script>
@endsection