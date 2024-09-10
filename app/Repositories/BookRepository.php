<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository
{

  public function getAllBooksWithFavoriteStatus(int $userId)
  {
    // Busca todos os livros com a coluna is_favorite usando o relacionamento com a tabela de Favorites.
    return Book::with('userWhoFavorited')
      ->get()
      ->map(function ($book) use ($userId) {
        // Checa se o usuario tem o livro favoritado.
        $book->is_favorite = $book->userWhoFavorited->contains($userId);
        return $book;
      });
  }
  public function getBooksByUserId(int|string $userId)
  {
    return Book::where('user_id', $userId)->get();
  }

  public function getBookByIdAndUserId(int|string $bookId, int|string $userId)
  {
    return Book::where('user_id', $userId)->where('id', $bookId)->first();
  }

  public function createBook(array $data)
  {
    return Book::create($data);
  }

  public function updateBook(int $bookId, array $data)
  {
    return Book::where('id', $bookId)->update($data);
  }

  public function deleteBook(int $bookId)
  {
    return Book::where('id', $bookId)->delete();
  }
}
