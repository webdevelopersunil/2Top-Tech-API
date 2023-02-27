<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $data;

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
            '{{Tech Name}}'         =>  $this->data['name'],
            '{{Restaurant Name}}'   =>  $this->data['restaurant_name'],
            '{{Job ID}}'      =>  $this->data['service_name'],
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
