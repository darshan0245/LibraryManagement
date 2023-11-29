<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Auth\ApiAuthController;
use Illuminate\Auth\AuthenticationException;
use App\Http\Controllers\IssueBookController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\TokenValidationController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => ['cors', 'json.response']], function () {
    // ...
});

/*Route::middleware([AdminMiddleware::class])->group(function () {
    Route::delete('/books/{id}', [BookController::class, 'deleteBook']);
});*/

Route::middleware('auth:api')->group(function () {
    //Route::get('/books/{id}', [BookController::class, 'retriveById'])->name('books.retrieve');
    Route::get('/logout', [ApiAuthController::class, 'logOut']);
    //Route::get('/books', [BookController::class, 'index']);
    Route::post('books/issue',[IssueBookController::class, 'store']);
    Route::get('books/issuedbooks', [IssueBookController::class, 'issuedBooks']);
    Route::post('/addbook', [BookController::class, 'store']);

});

//Route::get('/books', [BookController::class, 'index']);
//Route::post('books/issue',[IssueBookController::class, 'store']);
Route::get('/books/{id}', [BookController::class, 'retriveById'])->name('books.retrieve');
Route::delete('/books/{id}', [BookController::class, 'deleteBook'])->name('books.delete');
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::get('/books', [BookController::class, 'index']);
//Route::get('/logout', [ApiAuthController::class, 'logOut']);




// middleware route
//Route::middleware('auth:api')->post('books/issue',[IssueBookController::class, 'store']);
Route::post('/addbook',[BookController::class, 'addBook'])->middleware('auth:api');
Route::post('/editbook/{id}',[BookController::class, 'editBook']);
//Route::delete('/books/{id}',[BookController::class, 'deleteBook'])->middleware()


// check the auth token
Route::post('/validate-token', [TokenValidationController::class, 'checkToken']);