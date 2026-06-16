@extends('layouts.app')

@section('title', 'Создать пост')

@section('content')

<h1>Создать пост</h1>

<form method="POST" action="{{ route('posts.store') }}">

    @csrf

    <div>
        <label>Заголовок</label>

        <input
            type="text"
            name="title"
            value="{{ old('title') }}"
        >

        @error('title')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label>Текст поста</label>

        <textarea
            name="body"
            rows="10"
        >{{ old('body') }}</textarea>

        @error('body')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <br>

    <button type="submit">
        Создать
    </button>

</form>

@endsection
