@extends('layouts.app')

@section('title', $post->title)

@section('content')

<article>
    <h1>{{ $post->title }}</h1>

    <p>{{ $post->body }}</p>

    <small>
        Автор: {{ $post->author->name }}
        ·
        {{ $post->created_at->format('d.m.Y H:i') }}
    </small>
</article>

@can('update', $post)
    <a href="{{ route('posts.edit', $post) }}">
        Редактировать
    </a>
@endcan

@can('delete', $post)
    <form method="POST" action="{{ route('posts.destroy', $post) }}">
        @csrf
        @method('DELETE')

        <button type="submit">
            Удалить
        </button>
    </form>
@endcan

<hr>

<h2>Комментарии</h2>

@forelse ($post->comments as $comment)

    <div>
        <p>{{ $comment->body }}</p>

        <small>
            {{ $comment->author->name }}
            ·
            {{ $comment->created_at->format('d.m.Y H:i') }}
        </small>

        @can('delete', $comment)
            <form
                method="POST"
                action="{{ route('comments.destroy', $comment) }}"
            >
                @csrf
                @method('DELETE')

                <button type="submit">
                    Удалить
                </button>
            </form>
        @endcan
    </div>

    <hr>

@empty

    <p>Комментариев пока нет.</p>

@endforelse

@auth

<h3>Добавить комментарий</h3>

<form method="POST" action="{{ route('comments.store') }}">

    @csrf

    <input
        type="hidden"
        name="post_id"
        value="{{ $post->id }}"
    >

    <div>
        <textarea
            name="body"
            rows="5"
        >{{ old('body') }}</textarea>

        @error('body')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <br>

    <button type="submit">
        Отправить
    </button>

</form>

@endauth

@endsection
