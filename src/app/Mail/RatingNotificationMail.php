<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Rating;
use App\Models\Transaction;

class RatingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $rating;
    public $transaction;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Rating $rating, Transaction $transaction)
    {
        $this->rating = $rating;
        $this->transaction = $transaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('取引が完了しました')
        ->view('emails.rating_notification');
    }
}
