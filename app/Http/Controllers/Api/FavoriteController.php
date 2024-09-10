<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
  protected $favoriteService;

  public function __construct(FavoriteService $favoriteService)
  {
    $this->favoriteService = $favoriteService;
  }
  public function index(): JsonResponse
  {
    $userId = auth('api')->id();
    if (!isset($userId)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $favorites = $this->favoriteService->getUserFavorites($userId);

    return response()->json([
      'status' => 'success',
      'message' => 'Favorites retrieved',
      'favorites' => $favorites
    ]);
  }
  public function store(string|int $bookId): JsonResponse
  {
    $userId = auth('api')->id();
    if (!$userId) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $result = $this->favoriteService->addBookToFavorites($userId, $bookId);

    return response()->json($result);
  }
  public function destroy(string|int $bookId): JsonResponse
  {
    $userId = auth('api')->id();
    if (!$userId) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $result =  $this->favoriteService->removeBookFromFavorites($userId, $bookId);

    return response()->json($result);
  }
}
