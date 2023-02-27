<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RoSendOffer extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */


    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            '{{name}}'         =>  $this->data['name'],
            '{{job}}'          =>  $this->data['job_name'],
            '{{RO}}'           =>  $this->data['restaurant_name'],
        ];

        $emailTemplate = get_email_template($this->data['template']);

        $email_body = getFormattedEmailData($data, $emailTemplate->email_body);
        $subject = getFormattedEmailData($data, $emailTemplate->email_subject);

        return  $this->to($this->data['toEmail'])
                ->from(trim(config('mail.from.address')) )
                ->subject(trim($subject))
                ->view('emails.email')
                ->with('logo', '')
                ->with('email_body', trim($email_body));

    }
}
