<?php

namespace App\Jobs;

use App\Mail\BookCreatedMail;
use App\Models\Book;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendBookCreatedEmail implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $user;
  protected $book;

  /**
   * Create a new job instance.
   */
  public function __construct(User $user, Book $book)
  {
    $this->user = $user;
    $this->book = $book;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    \Log::info('Sending the mail...');

    Mail::to(env('MAIL_FROM_ADDRESS'))->send(new BookCreatedMail($this->user, $this->book));
  }
}
