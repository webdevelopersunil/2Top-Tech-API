<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignUpMail extends Mailable
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

        $data = [
            '{{name}}' => $this->user->first_name,
        ];

        $emailTemplate = get_email_template('thankyou_email_after_verify_account');

        $email_body = getFormattedEmailData($data, $emailTemplate->email_body);
        $subject = getFormattedEmailData($data, $emailTemplate->email_subject);

        return $this->to($this->user->email)
            ->from(trim(config('mail.from.address')) )
            ->subject(trim($subject))
            ->view('emails.email')
            ->with('logo', '')
            ->with('email_body', trim($email_body));

    }
}
