<?php

namespace App\Repositories;
use App\Models\Book;

class BookRepository
{
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