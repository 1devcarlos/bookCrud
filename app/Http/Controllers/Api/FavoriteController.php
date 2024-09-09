<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
  public function index(): JsonResponse
  {
    $userId = auth('api')->id();
    if (!isset($userId)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $favorites = DB::table('favorite_books')->where('user_id', $userId)->get();
    $favoritesArr = (array) [];
    foreach ($favorites as $fav) {
      array_push($favoritesArr, $fav);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Favorites retrieved',
      'favorites' => $favoritesArr
    ]);
  }
  public function store(string|int $bookId): JsonResponse
  {
    $userId = auth('api')->id();
    if (!$userId) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $book = DB::table('books')->where('id', $bookId)->first();
    if (!$book || $book->user_id !== $userId) {
      return response()->json([
        'status' => 'error',
        'message' => 'The book you are trying to access it does not exist or does not belong to you.',
      ]);
    }

    $alreadyFavorite = DB::table('favorite_books')->where('user_id', $userId)->where('book_id', $bookId)->first();
    if (isset($alreadyFavorite)) {
      return response()->json([
        'status' => 'error',
        'message' => 'This book is already favorited by this user',
        'favorite' => null
      ]);
    }

    DB::table('favorite_books')->insert(['user_id' => $userId, 'book_id' => $bookId]);

    return response()->json([
      'status' => 'success',
      'message' => "Book successfully added to user's favorite"
    ]);
  }
  public function destroy(string|int $bookId): JsonResponse
  {
    $userId = auth('api')->id();
    if (!$userId) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }
    $book = DB::table('books')->where('id', $bookId)->first();
    if (!$book || $book->user_id !== $userId) {
      return response()->json([
        'status' => 'error',
        'message' => 'The book you are trying to access it does not exist or does not belong to you.',
      ]);
    }

    $isFavorite = DB::table('favorite_books')->where('user_id', $userId)->where('book_id', $bookId)->first();

    if (!isset($isFavorite)) {
      return response()->json([
        'status' => 'error',
        'message' => 'This book is not favorited by the user.',
        'favorite' => null
      ]);
    }

    DB::table('favorite_books')->where('user_id', $userId)->where('book_id', $bookId)->delete();
    return response()->json([
      'status' => 'success',
      'message' => "Book successfully removed from user's favorite",
    ]);
  }
}
