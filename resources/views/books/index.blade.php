@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Books</h1>

    {{-- Add Book Button for Admins --}}
    @if (auth()->user()->role_id == 1)
        <a href="{{ route('books.create') }}" class="btn btn-primary mb-3">Add Book</a>
    @endif

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Books Table --}}
    <table id="books-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Availability</th>
                @if (auth()->user()->role_id == 2)
                    <th>Borrow</th>
                    <th>Return</th>
                @endif
                @if (auth()->user()->role_id == 1)
                    <th>Borrow History</th>
                    <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->year }}</td>
                    <td>{{ $book->is_available ? 'Available' : 'Not Available' }}</td>

                    @if (auth()->user()->role_id == 2)
                        {{-- Borrow Button --}}
                        <td>
                            @if ($book->is_available)
                                <a href="#" class="btn btn-info borrow btn-sm" data-id="{{ $book->id }}">Borrow</a>
                            @endif
                        </td>
                        {{-- Return Button --}}
                        <td>
                            @php
                                $borrowed = $book->borrowLogs->where('user_id', auth()->user()->id)->whereNull('returned_at')->first();
                            @endphp
                            @if ($borrowed && !$borrowed->returned_at)
                                <a href="#" class="btn btn-warning return btn-sm" data-id="{{ $book->id }}">Return</a>
                            @endif
                        </td>
                    @endif

                    @if (auth()->user()->role_id == 1)
                        {{-- Borrow History --}}
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
                        </td>
                        {{-- Edit and Delete Actions --}}
                        <td>
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Export PDF Button --}}
    <button id="export-pdf" class="btn btn-success mt-3">Export PDF</button>
</div>
@endsection

@push('scripts')
<script>
    // DataTable Initialization
    $('#books-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('books.fetch') }}",
        columns: [
            { data: 'title' },
            { data: 'author' },
            { data: 'year' },
            { data: 'availability', render: function (data) {
                return data == 'Available' ? 'Available' : 'Not Available';
            }},
            @if (auth()->user()->role_id == 2)
                { data: 'borrow', orderable: false, searchable: false },
                { data: 'return', orderable: false, searchable: false },
            @endif
            @if (auth()->user()->role_id == 1)
                { data: 'borrow_history', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false },
            @endif
        ]
    });

    // Borrow Book
    $(document).on('click', '.borrow', function (e) {
        e.preventDefault();
        const bookId = $(this).data('id');
        $.ajax({
            url: `/books/${bookId}/borrow`,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                alert(response.message);
                    window.location.reload();

                $('#books-table').DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                alert('Something went wrong!');
            }
        });
    });

    // Return Book
    $(document).on('click', '.return', function (e) {
        e.preventDefault();
        const bookId = $(this).data('id');
        $.ajax({
            url: `/books/${bookId}/return`,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                alert(response.message);
                    window.location.reload();

                $('#books-table').DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                alert('Something went wrong!');
            }
        });
    });

    // Export PDF
    $('#export-pdf').on('click', function () {
        window.location.href = "{{ route('books.exportPDF') }}";
    });
</script>
@endpush
