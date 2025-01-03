<?php

namespace App\Repositories;

use App\Interfaces\BookRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\BorrowLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use Yajra\DataTables\Facades\DataTables;

class BookRepository implements BookRepositoryInterface
{
    protected $bookModel, $borrowLogModel;

    public function __construct(Book $bookModel, BorrowLog $borrowLogModel)
    {
        $this->bookModel = $bookModel;
        $this->borrowLogModel = $borrowLogModel;
    }

    // Fetch all books and return the view
    public function all()
    {
        $books = $this->bookModel->with('borrowLogs')->get();
        $user = Auth::user();
        return view('books.index', compact('books', 'user'));
    }

    // Fetch books with DataTables integration
    public function fetchBooks($request)
    {
        if ($request->ajax()) {
            $books = $this->bookModel->with(['borrowLogs.user'])->select(['id', 'title', 'author', 'year', 'is_available']);

            return DataTables::of($books)
                ->addColumn('availability', fn($book) => $book->is_available ? 'Available' : 'Not Available')
                ->addColumn('borrow', fn($book) => $book->is_available ? '<a href="#" class="btn btn-info borrow btn-sm" data-id="' . $book->id . '">Borrow</a>' : '')
                ->addColumn('return', function ($book) {
                    $borrowLog = $book->borrowLogs->where('user_id', Auth::user()->id)->whereNull('returned_at')->first();
                    return ($borrowLog && !$borrowLog->returned_at)
                        ? '<a href="#" class="btn btn-warning return btn-sm" data-id="' . $book->id . '">Return</a>'
                        : '';
                })
                ->addColumn('borrow_history', function ($book) {
                    if (Auth::user()->role_id == 1) {
                        return $book->borrowLogs->map(function ($log) {
                            return 'Borrowed by: ' . $log->user->name . ' on ' . $log->borrowed_at .
                                ($log->returned_at ? ' | Returned on ' . $log->returned_at : ' | Not yet returned');
                        })->implode('<br>') ?: 'No borrow history';
                    }
                    return '';
                })
                ->addColumn('action', function ($book) {
                    return '<a href="' . route('books.edit', $book->id) . '" class="btn btn-warning btn-sm">Edit</a>
                            <form action="' . route('books.destroy', $book->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>';
                })
                ->rawColumns(['borrow', 'return', 'borrow_history', 'action'])
                ->make(true);
        }
    }

    // Borrow a book
    public function borrow($request, $id)
    {
        $book = $this->bookModel->findOrFail($id);
        $book->update(['is_available' => false]);

        $this->borrowLogModel->create([
            'user_id' => Auth::user()->id,
            'borrowable_id' => $book->id,
            'borrowable_type' => $this->bookModel::class,
            'borrowed_at' => now(),
        ]);

        return response()->json(['message' => 'Book borrowed successfully!']);
    }

    // Return a borrowed book
    public function returnBook($request, $id)
    {
        $book = $this->bookModel->findOrFail($id);
        $borrowLog = $book->borrowLogs()
            ->where('user_id', Auth::user()->id)
            ->whereNull('returned_at')
            ->first();

        if ($borrowLog) {
            $borrowLog->update(['returned_at' => now()]);
            $book->update(['is_available' => true]);
            return response()->json(['message' => 'Book returned successfully!']);
        }

        return response()->json(['message' => 'You have not borrowed this book or it is already returned.'], 400);
    }

    // Export books data to a PDF
    public function exportPDF()
    {
        $user = Auth::user();
        
        // Check if the user is an admin
        if ($user->role_id == 1) {
            // Admin: Fetch borrow and return history for all books and users
            $books = $this->bookModel->with(['borrowLogs.user'])->get();
        } else {
            // Non-admin: Fetch only the borrow and return history for the logged-in user
            $books = $this->bookModel->with(['borrowLogs' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])->get();
        }
        
        // Load the PDF view with the books and their borrow logs
        $pdf = Pdf::loadView('books.pdf', compact('books'));
        return $pdf->download('books.pdf');
    }
    
    

    // Show the form to create a new book
    public function create()
    {
        return view('books.create');
    }

    // Store a new book
    public function store($request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'year' => 'nullable|integer',
        ]);

        $this->bookModel->create([
            'title' => $request->title,
            'author' => $request->author,
            'year' => $request->year,
            'is_available' => true,
        ]);

        return redirect()->route('books.index')->with('success', 'Book added successfully.');
    }

    // Show the form to edit an existing book
    public function edit($id)
    {
        $book = $this->bookModel->with('borrowLogs')->findOrFail($id);
        return view('books.edit', compact('book'));
    }

    // Update an existing book
    public function update($request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'year' => 'nullable|integer',
        ]);

        $book = $this->bookModel->findOrFail($id);
        $book->update($request->only(['title', 'author', 'year']));

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    // Delete a book
    public function destroy($id)
    {
        $this->bookModel->destroy($id);
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
