<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $itemTitle,
        public ?string $paymentCheckoutUrl = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking received — '.$this->booking->booking_code.' — Jet Fly Airways',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.bookings.placed',
        );
    }
}
