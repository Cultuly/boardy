@extends('layouts.app')

@section('content')

<h1>Редактировать пост</h1>

<form method="POST" action="{{ route('posts.update', $post) }}">
    @csrf
    @method('PUT')

    <input name="title" value="{{ old('title', $post->title) }}">
    
    <textarea name="body">{{ old('body', $post->body) }}</textarea>

    <button>Сохранить</button>
</form>

@endsection
