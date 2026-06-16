@extends('layouts.app')

@section('title', 'Посты')

@section('content')

<h1>Все посты</h1>

@forelse ($posts as $post)

    <article>
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

    <p>Постов пока нет.</p>

@endforelse

{{ $posts->links() }}

@endsection
