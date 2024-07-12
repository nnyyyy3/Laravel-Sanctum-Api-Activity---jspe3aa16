
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>Comments</h2>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('comments.store') }}">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <div class="form-group">
                        <label for="content">Comment</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Comment</button>
                </form>
            </div>
        </div>
        <div class="mt-4">
            @foreach($post->comments as $comment)
                <div class="card mb-2">
                    <div class="card-body">
                        <p class="card-text">{{ $comment->content }}</p>
                        <small>Commented by {{ $comment->user->name }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
