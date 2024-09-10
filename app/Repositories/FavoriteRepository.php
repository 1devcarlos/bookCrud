<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\FavoriteBook;

class FavoriteRepository
{
  public function getUserFavorites(int $userId)
  {
    return Book::whereHas('userWhoFavorited', function ($query) use ($userId) {
      $query->where('user_id', $userId);
    })->get();
  }

  public function findBookById(int|string $bookId)
  {
    return Book::find($bookId);
  }

  public function findFavoriteBook(int $userId, int|string $bookId)
  {
    return FavoriteBook::where('user_id', $userId)->where('book_id', $bookId)->first();
  }

  public function addFavorite(int $userId, int|string $bookId)
  {
    // Atualiza a coluna 'is_favorite' na tabela 'books'
    Book::where('id', $bookId)->update(['is_favorite' => true]);

    // Adiciona o livro aos favoritos de fato.
    return FavoriteBook::create(['user_id' => $userId, 'book_id' => $bookId]);
  }

  public function removeFavorite(int $userId, int|string $bookId)
  {
    // Remove o favorito e atualiza a coluna 'is_favorite' da tabela 'books'
    Book::where('id', $bookId)->update(['is_favorite' => false]);

    return FavoriteBook::where('user_id', $userId)->where('book_id', $bookId)->delete();
  }
}
