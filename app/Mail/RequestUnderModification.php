<?php

// app/Mail/RequestUnderModification.php
namespace App\Mail;

use App\Models\DoctorMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestUnderModification extends Mailable
{
    use Queueable, SerializesModels;

    public DoctorMail $mail;

    public function __construct(DoctorMail $mail)
    {
        $this->mail = $mail;
    }

    public function build()
    {
        return $this->subject('طلبك قيد التعديل')
                    ->view('emails.under_modification', [
                        'mail' => $this->mail,
                    ]);
    }
}
