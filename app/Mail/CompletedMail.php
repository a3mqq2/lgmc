<?php

namespace App\Mail;

use App\Models\DoctorMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompletedMail extends Mailable
{

    use Queueable, SerializesModels;

    public DoctorMail $mail;

    public function __construct(DoctorMail $mail)
    {
        $this->mail = $mail;
    }

    public function build()
    {
        return $this->subject('تم إكمال طلبك')
                    ->view('emails.completed_mail', [
                        'mail' => $this->mail,
                    ]);
    }
}
