<!DOCTYPE html>
<html>
<head>
    <title>Books List</title>
</head>
<body>
    <h1>Books List</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Availability</th>
                    <th>Borrow History</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
            <tr>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->availability ? 'Available' : 'Not Available' }}</td>

                @if (Auth::user()->role_id == 1) 
                    <td>
                        @foreach ($book->borrowLogs as $log)
                            Borrowed by: {{ $log->user->name }} on {{ $log->borrowed_at }} 
                            @if ($log->returned_at)
                                | Returned on {{ $log->returned_at }}
                            @else
                                | Not yet returned
                            @endif
                            <br>
                        @endforeach
                        @if ($book->borrowLogs->isEmpty())
                            No borrow history
                        @endif
                    </td>
                @elseif (Auth::user()->role_id != 1)
                    <td>
                        @foreach ($book->borrowLogs as $log)
                            @if ($log->user_id == Auth::user()->id)
                                Borrowed on: {{ $log->borrowed_at }} 
                                @if ($log->returned_at)
                                    | Returned on {{ $log->returned_at }}
                                @else
                                    | Not yet returned
                                @endif
                                <br>
                            @endif
                        @endforeach
                        @if ($book->borrowLogs->isEmpty())
                            No borrow history
                        @endif
                    </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
