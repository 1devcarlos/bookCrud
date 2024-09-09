<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use DB;
use Illuminate\Http\Request;
use Validator;

class BookController extends Controller
{
  public function index()
  {
    $userId = auth('api')->id();
    if (!isset($userId)) {
      return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized',
        'books' => null
      ]);
    }
    $books = DB::table('books')->where('user_id', $userId)->get();
    if (!isset($books)) {
      return response()->json([
        'status' => 'error',
        'message' => 'No books found',
        'books' => null
      ]);
    }

    $bookArr = (array) [];
    foreach ($books as $book) {
      array_push($bookArr, $book);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Books successfully retrieved',
      'books' => $bookArr
    ]);
  }

  public function show(string|int $id)
  {
    $userId = auth('api')->id();
    if (!isset($userId)) {
      return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized',
        'books' => null
      ]);
    }
    $book = DB::table('books')->where('user_id', $userId)->where('id', $id)->first();
    if ($userId !== $book->user_id) {
      return response()->json([
        'status' => 'error',
        'message' => 'The book you are trying to access does not belong to you.',
        'book' => null
      ]);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Book successfully retrieved!',
      'book' => $book
    ]);

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

    $book = auth('api')->user()->books()->create($request->only('title', 'description'));

    return response()->json($book, 201);
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
    $book = DB::table('books')->where('user_id', $userId)->where('id', $bookId)->first();
    if (!isset($book)) {
      return response()->json([
        'status' => 'error',
        'message' => 'Book not found. Please verify the id of the book you are trying to access it.',
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

    DB::table('books')->where('id', $bookId)->update($request->only('title', 'description'));

    $updatedBook = DB::table('books')->where('id', $bookId)->first();

    return response()->json([
      'status' => 'success',
      'message' => 'Book successfully updated!',
      'book' => $updatedBook
    ]);

  }

  public function destroy(string|int $bookId)
  {
    $userId = auth('api')->id();
    $book = DB::table('books')->where('id', $bookId)->first();
    if ($book->user_id !== $userId) {
      return response()->json([
        'status' => 'error',
        'message' => 'The book you are trying to access it does not belong to you.',
        'book' => null
      ]);
    }

    DB::table('books')->where('id', $bookId)->delete();

    return response()->json([
      'status' => 'success',
      'message' => 'Book successfully deleted'
    ]);
  }
}
