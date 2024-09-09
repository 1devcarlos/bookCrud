<?php
namespace App\Services;
use App\Repositories\BookRepository;

class BookService
{
  protected $bookRepository;

  public function __construct(BookRepository $bookRepository)
  {
    $this->bookRepository = $bookRepository;
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
    return $this->bookRepository->createBook($data);
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