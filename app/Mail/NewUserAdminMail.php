<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_body = '<p>Hello Admin,</p>
                    <p>A new user has been registered for application. Please check below details: </p>
                    <p>Name: '. $this->user->first_name. ' '. $this->user->last_name. '</p>
                    <p>Email: '. $this->user->email. '</p>
                    <p>Thankyou</p>';

        $subject = "A new user registered";

        return $this->to(env('ADMIN_EMAIL'))
            ->from(trim(config('mail.from.address')) )
            ->subject(trim($subject))
            ->view('emails.email')
            ->with('logo', '')
            ->with('email_body', trim($email_body));

    }
}
