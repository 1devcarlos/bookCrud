<?php

namespace App\Services;

use App\Jobs\SendBookCreatedEmail;
use App\Repositories\BookRepository;
use Auth;

class BookService
{
  protected $bookRepository;

  public function __construct(BookRepository $bookRepository)
  {
    $this->bookRepository = $bookRepository;
  }

  public function getAllBooksForUser(int $userId)
  {
    return $this->bookRepository->getAllBooksWithFavoriteStatus($userId);
  }

  public function getBooksByUserId(string|int $userId)
  {
    return $this->bookRepository->getBooksByUserId($userId);
  }

  public function getBookByIdAndUserId(string|int $bookId, string|int $userId)
  {
    return $this->bookRepository->getBookByIdAndUserId($bookId, $userId);
  }

  public function createBook(array $data)
  {
    // return $this->bookRepository->createBook($bookId, $data);
    $book = $this->bookRepository->createBook($data);

    $user = auth('api')->user();

    SendBookCreatedEmail::dispatch($user, $book);

    return $book;
  }

  public function updateBook(string|int $bookId, array $data)
  {
    return $this->bookRepository->updateBook($bookId, $data);
  }

  public function deleteBook(string|int $bookId)
  {
    return $this->bookRepository->deleteBook($bookId);
  }
}
