@extends('layouts.app')

@section('title', '投稿詳細')

@section('content')

    @if (session('flash_message'))
        <p class="text-success">{{ session('flash_message') }}</p>
    @endif

    <div class="mb-2">
        <a href="{{ route('posts.index') }}" class="text-decoration-none">&lt; 戻る</a>
    </div>

    <article>
        <div class="card mb-3">
            <div class="card-body">
                <h2 class="card-title fs-5">{{ $post->title }}</h2>
                <p class="card-text">{{ $post->content }}</p>
                <p>{{ $post->updated_at }}</p>

                @if ($post->user_id === Auth::id())
                <div class="d-flex">
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-primary d-block me-1">編集</a>

                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('本当に削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">削除</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </article>
@endsection
