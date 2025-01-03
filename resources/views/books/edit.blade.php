@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Book</h1>
    <div class="card">
    <div class="card-header">Borrow Logs</div>
    <div class="card-body">
        @if($book->borrowLogs->isEmpty())
            <p>No borrow logs for this book.</p>
        @else
            <ul>
                @foreach($book->borrowLogs as $log)
                    <li>
                        Borrowed by User ID: {{ $log->user_id }} on {{ $log->borrowed_at }} 
                        @if ($log->returned_at)
                            - Returned on {{ $log->returned_at }}
                        @else
                            - Not yet returned
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
    <form action="{{ route('books.update', $book->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="{{ $book->title }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" value="{{ $book->author }}" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Year</label>
            <input type="number" name="year" value="{{ $book->year }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Update Book</button>
    </form>
</div>
@endsection
