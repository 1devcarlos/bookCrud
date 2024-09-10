<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{

  protected $bookService;
  public function __construct(BookService $bookService)
  {
    $this->bookService = $bookService;
  }
  public function index()
  {
    $userId = auth('api')->id();
    if (!$userId) {
      return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized',
        'books' => null
      ], 401);
    }
    $books = $this->bookService->getAllBooksForUser($userId);

    return response()->json([
      'status' => 'success',
      'message' => 'Books successfully retrieved',
      'books' => $books
    ], 200);
  }

  public function show(string|int $id)
  {
    $userId = auth('api')->id();
    if (!isset($userId)) {
      return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized',
        'book' => null
      ], 401);
    }

    $book = $this->bookService->getBookByIdAndUserId($id, $userId);

    if (!$book) {
      return response()->json([
        'status' => 'error',
        'message' => 'Book not found or does not belong to you',
        'book' => null
      ], 404);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Book successfully retrieved!',
      'book' => $book
    ], 200);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string|between:3,255',
      'description' => 'required|string'
    ]);

    if ($validator->fails()) {
      return response()->json([$validator->errors()], 400);
    }

    $book = $this->bookService->createBook([
      'title' => $request->title,
      'description' => $request->description,
      'user_id' => auth('api')->id(),
    ]);

    return response()->json([
      'status' => 'success',
      'message' => 'Book successfully created',
      'book' => $book
    ], 201);
  }

  public function update(Request $request, string|int $bookId)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'nullable|string|between:3,255',
      'description' => 'nullable|string'
    ]);

    if ($validator->fails()) {
      return response()->json([$validator->errors()], 400);
    }

    if (!$request->hasAny(['title', 'description'])) {
      return response()->json([
        'status' => 'error',
        'message' => 'At least one field (title or description) must be provided.'
      ], 400);
    }

    $userId = auth('api')->id();
    if (!isset($userId)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }
    $book = $this->bookService->getBookByIdAndUserId($bookId, $userId);

    if (!$book) {
      return response()->json([
        'status' => 'error',
        'message' => 'Book not found or does not belong to you',
        'book' => null
      ]);
    }

    if ($book->user_id !== $userId) {
      return response()->json([
        'status' => 'error',
        'message' => 'The book you are trying to access it does not belong to you.',
        'book' => null
      ]);
    }

    $updatedBook = $this->bookService->updateBook($bookId, $request->only('title', 'description'));

    return response()->json([
      'status' => 'success',
      'message' => 'Book successfully updated!',
      'book' => $updatedBook
    ], 200);
  }

  public function destroy(string|int $bookId)
  {
    $userId = auth('api')->id();

    $book = $this->bookService->getBookByIdAndUserId($bookId, $userId);

    if (!$book || $book->user_id !== $userId) {
      return response()->json([
        'status' => 'error',
        'message' => 'Book not found or does not belong to you',
        'book' => null
      ]);
    }

    $this->bookService->deleteBook($bookId);

    return response()->json([
      'status' => 'success',
      'message' => 'Book successfully deleted'
    ], 200);
  }
}
