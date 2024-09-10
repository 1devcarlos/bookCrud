<?php

namespace App\Services;

use App\Repositories\FavoriteRepository;

class FavoriteService
{
  protected $favoriteRepository;

  public function __construct(FavoriteRepository $favoriteRepository)
  {
    $this->favoriteRepository = $favoriteRepository;
  }

  public function getUserFavorites(int $userId)
  {
    return $this->favoriteRepository->getUserFavorites($userId);
  }

  public function addBookToFavorites(int $userId, int|string $bookId)
  {
    $book = $this->favoriteRepository->findBookById($bookId);

    if (!$book || $book->user_id !== $userId) {
      return ['status' => 'error', 'message' => 'The book you are trying to access does not exist or does not belong to you.'];
    }

    $alreadyFavorite = $this->favoriteRepository->findFavoriteBook($userId, $bookId);

    if ($alreadyFavorite) {
      return ['status' => 'error', 'message' => 'This book is already favorited by this user'];
    }

    $this->favoriteRepository->addFavorite($userId, $bookId);

    return ['status' => 'success', 'message' => 'Book successfully added to user\'s favorite'];
  }

  public function removeBookFromFavorites(int $userId, int|string $bookId)
  {
    $book = $this->favoriteRepository->findBookById($bookId);

    if (!$book || $book->user_id !== $userId) {
      return ['status' => 'error', 'message' => 'The book you are trying to access does not exist or does not belong to you.'];
    }

    $isFavorite = $this->favoriteRepository->findFavoriteBook($userId, $bookId);

    if (!$isFavorite) {
      return ['status' => 'error', 'message' => 'This book is not favorited by the user.'];
    }

    $this->favoriteRepository->removeFavorite($userId, $bookId);

    return ['status' => 'success', 'message' => 'Book successfully removed from user\'s favorite'];
  }
}
