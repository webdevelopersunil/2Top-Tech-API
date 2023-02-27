<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessNewPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         $email_body = '<p>'. __('Password successfully set up.') .'</p>
                    <p>'. __('Congratulation, Your new password has been set-up Successfully.').' </p>';

        return $this->to($this->details['toEmail'])
            ->from(trim(config('mail.from.address')) )
            ->subject(trim($this->details['subject']))
            ->view('emails.email')
            ->with('logo', '')
            ->with('email_body', trim($email_body));

    }
}
