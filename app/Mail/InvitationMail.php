<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $tempPassword;

    public function __construct(User $user, $tempPassword)
    {
        $this->user = $user;
        $this->tempPassword = $tempPassword;
    }

    public function build()
    {
        return $this->subject('Invitation to URL Shortener Service')
            ->markdown('emails.invitation')
            ->with([
                'user' => $this->user,
                'tempPassword' => $this->tempPassword,
                'loginUrl' => url('/login'),
            ]);
    }
}
