<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MedicalFacilityRejectMail extends Mailable
{
    use Queueable, SerializesModels;

    public $medicalFacility;
    public $editReason;

    /**
     * Create a new message instance.
     */
    public function __construct($medicalFacility, string $editReason)
    {
        $this->medicalFacility = $medicalFacility;
        $this->editReason = $editReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'طلب تعديل بيانات المنشأة الطبية - ' . $this->medicalFacility->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.rejection-medical-facility-email',
            with: [
                'medicalFacility' => $this->medicalFacility,
                'editReason' => $this->editReason,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
