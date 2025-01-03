<?php
namespace App\Http\Controllers;

use App\Models\Book;

use Illuminate\Http\Request;

use App\Interfaces\BookRepositoryInterface;


class BookController extends Controller
{
    protected $bookRepositoryInterface;

    public function __construct(BookRepositoryInterface $bookRepositoryInterface)
    {
        $this->bookRepositoryInterface = $bookRepositoryInterface;
    }

    public function index()
    {
        return  $this->bookRepositoryInterface->all();
        // $books = Book::with('borrowLogs')->get();
        // $user = Auth::user();
        // return view('books.index', compact('books', 'user'));
    }

    public function fetchBooks(Request $request)
    {
        return  $this->bookRepositoryInterface->fetchBooks($request);
        // if ($request->ajax()) {
        //     $books = Book::with(['borrowLogs.user'])->select(['id', 'title', 'author', 'year', 'is_available']);
            
        //     return DataTables::of($books)
        //         ->addColumn('availability', function ($book) {
        //             return $book->is_available  == 1 ? 'Available' : 'Not Available';
        //         })
        //         ->addColumn('borrow', function ($book) {
        //             return '<a href="#" class="btn btn-info borrow btn-sm" data-id="' . $book->id . '">Borrow</a>';
        //         })
        //         ->addColumn('return', function ($book) {
        //             // Check if the logged-in user has borrowed this book
        //             $borrowLog = $book->borrowLogs->where('user_id', Auth::user()->id)->first();
        //             if ($borrowLog && !$borrowLog->returned_at) {
        //                 return '<a href="#" class="btn btn-warning return btn-sm" data-id="' . $book->id . '">Return</a>';
        //             }
        //             return ''; // No return button if the book is not borrowed or already returned
        //         })
        //         ->addColumn('borrow_history', function ($book) {
        //             // Only display borrow history for admins
        //             if (Auth::user()->role_id == 1) {
        //                 $history = '';
        //                 foreach ($book->borrowLogs as $log) {
        //                     $history .= 'Borrowed by: ' . $log->user->name . ' on ' . $log->borrowed_at . 
        //                                 ($log->returned_at ? ' | Returned on ' . $log->returned_at : ' | Not yet returned') . '<br>';
        //                 }
        //                 return $history ?: 'No borrow history';
        //             }
        //             return ''; // Hide borrow history for non-admin users
        //         })
        //         ->addColumn('action', function ($book) {
        //             return '<a href="' . route('books.edit', $book->id) . '" class="btn btn-warning btn-sm">Edit</a>
        //                     <form action="' . route('books.destroy', $book->id) . '" method="POST" style="display:inline;">
        //                         ' . csrf_field() . method_field('DELETE') . '
        //                         <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        //                     </form>';
        //         })
        //         ->rawColumns(['borrow', 'return', 'borrow_history', 'action']) // Allow HTML in columns
        //         ->make(true);
        // }
    }
    
    
 
    public function borrow(Request $request, $id)
    {
        return  $this->bookRepositoryInterface->borrow($request, $id);
        // $book = Book::findOrFail($id);
        // $book->update(['is_available' => false]);

        // BorrowLog::create([
        //     'user_id' => Auth::user()->id,
        //     'borrowable_id' => $book->id,
        //     'borrowable_type' => Book::class,
        //     'borrowed_at' => now(),
        // ]);

        // return response()->json(['message' => 'Book borrowed successfully!']);
    }

    public function exportPDF()
    {
        return  $this->bookRepositoryInterface->exportPDF();
        // $books = Book::all();
        // $pdf = PDF::loadView('books.pdf', compact('books'));
        // return $pdf->download('books.pdf');
    }
    // Show Create Form
    public function create()
    {
        return  $this->bookRepositoryInterface->create();
        //return view('books.create');
    }
    // Store New Book
    public function store(Request $request)
    {
        return  $this->bookRepositoryInterface->store($request);
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'author' => 'required|string|max:255',
        //     'year' => 'nullable|integer',
        // ]);

        // $book = Book::create([
        //     'title' => $request->title,
        //     'author' => $request->author,
        //     'year' => $request->year,
        //     'is_available' => true,
        // ]);

        // return redirect()->route('books.index', compact('book'))->with('success', 'Book added successfully.');
    }

    // Show Edit Form
    public function edit($id)
    {
        return  $this->bookRepositoryInterface->edit($id);
        // $book = Book::with('borrowLogs')->findOrFail($id); // Retrieve the book by ID
        // return view('books.edit', compact('book')); // Return the edit form
    }

    // Update Book
    public function update(Request $request, $id)
    {
        return  $this->bookRepositoryInterface->update($request, $id);
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'author' => 'required|string|max:255',
        //     'year' => 'nullable|integer',
        // ]);
    
        // $book = Book::find($id);
        // $book->title = $request->title;
        // $book->author = $request->author;
        // $book->year = $request->year;
        // $book->save();
    
        // return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }    

    // Delete Book
    public function destroy($id)
    {
        return  $this->bookRepositoryInterface->destroy($id);
        // $book = Book::destroy($id);
        // return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    // Return Book
    public function returnBook(Request $request, $id)
    {
        return  $this->bookRepositoryInterface->returnBook($request, $id);
        // $book = Book::findOrFail($id);
        // $borrowLog = $book->borrowLogs()->where('user_id', Auth::user()->id)->whereNull('returned_at')->first();

        // if ($borrowLog) {
        //     $borrowLog->returned_at = now();
        //     $borrowLog->save();

        //     // Update the book availability
        //     $book->update(['is_available' => true]);

        //     return response()->json(['message' => 'Book returned successfully!']);
        // }

        // return response()->json(['message' => 'You have not borrowed this book or it is already returned.'], 400);
    }
}
