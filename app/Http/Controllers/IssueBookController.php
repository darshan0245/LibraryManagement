<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book; // Import your Book model
use App\Models\Issue_Book; // Import your IssueBook model
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Carbon\Carbon;

class IssueBookController extends Controller
{
    public function store(Request $request)
    {   
        // Validate the request data
        $validatedData = $request->validate([
            'book_name' => 'required|string|max:100',
            'approx_due_date' => 'required|date_format:m/d/Y',
        ]);


        // Find the book by name
        $book = Book::where('book_name', $validatedData['book_name'])->first();

        if (!$book) {
            return response()->json(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        // Get the number of available copies
        $availableCopies = $book->copies;

        if ($availableCopies < 1) {
            $issuedBook = Issue_Book::where('book_id', $book->id)->first();


            if ($issuedBook) {
                $userWhoIssued = User::find($issuedBook->user_id);

                if ($userWhoIssued) {
                    return response()->json([
                    'message' => 'Book is currently not available',
                    'issued_by' => 'this book is issued by ' .$userWhoIssued->name,
                ], Response::HTTP_CONFLICT);
                }
                
            }
            return response()->json(['error' => 'Book not available'], Response::HTTP_CONFLICT);
        }

    $user = auth()->user();

        $dueDate = Carbon::createFromFormat('m/d/Y', $validatedData['approx_due_date'])->format('Y-m-d');

        $issueData = [
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'user_name' => $user->name,
            'book_name' => $book->book_name,
            'issue_date' => now(),
            'due_date' => $dueDate,
        ];

        Issue_Book::create($issueData);

        // Decrement the copies count for the book
        $book->decrement('copies');

        return response()->json(['message' => 'Book issued successfully'], Response::HTTP_CREATED);
    }

    public function issuedBooks(Request $request)
    {
        $totalIssuedBook = Issue_Book::all();

            return response()->json([
            'issuedBooks' => $totalIssuedBook
        ], 200);
        
    }
  
}
