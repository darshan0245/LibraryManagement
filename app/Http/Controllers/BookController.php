<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\EditBook;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class BookController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    public function index()
    {
        $books = Book::all();

        return response()->json([
            'books' => $books
        ], 200)->header('Content-Type', 'application/json');
    }


    public function retriveById($id)
    {
        $book = Book::find($id);
        if ($book)
        {
            $bookName = $book->book_name;
            return response()->json(['book_name' => $bookName]);
        }
        else
        {
            return response()->json(['error' => 'book not found']);
        }

    }

    public function addBook(Request $request)
    {
        $bookData = $request->validate([
            'book_name' => 'required|string|max:50',
            'book_author' => 'required|string|max:50',
            'category' => 'required|string|max:50',
            'language' => 'required|string|max:50',
            'copies' =>'required|int|',
        ]);

        Book::create($bookData);

        return response()->json(['message' => 'book added to the library sucessfully.']);

    }

    public function editBook(Request $request, $id)
    {
        //get the user id of  authenticated user
        $userId = auth()->user()->id;

        $bookData = $request->validate([
            'book_name' => 'string|max:50',
            'book_author' => 'string|max:50',
            'category' => 'string|max:50',
            'language' => 'string|max:50',
            'copies' => 'int',
        ]);

        $book = Book::find($id);

        if (!$book)
        {
            return response()->json(['message' => 'book not found'], 404);
        }

        //check if user id is exist
        if ($userId)
        {
            //update the book data
            $book->update($bookData);

            //store the updated book data in the editBook model
            $editBook = new EditBook();
            $editBook->user_id = $userId;
            $editBook->book_id = $book->id;
            $editBook->book_name = $bookData['book_name'];
            $editBook->save();

            return response()->json(['message' => 'book updated sucessfully'], 200);
        }
        else
        {
            return response()->json(['error' => 'unauthenticated'], 403);
        }
    }

        public function deleteBook(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'unauthenticated'], 403);
        }

        $bookId = Book::find($id);

        if (!$bookId) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if ($user->is_admin) {
            $bookId->delete();
            return response()->json(['message' => 'Book deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

}