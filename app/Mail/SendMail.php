<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
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
        // trim(config('mail.from.address'))
        // ->from( ['address' => trim(config('mail.from.address')), 'name' => '2TopTech'] )
        return $this->to($this->data['toMail'])
            ->from(trim(config('mail.from.address')))
            ->subject(trim($this->data['subject']))
            ->view('emails.email')
            ->with('logo', '')
            ->with('email_body', trim($this->data['email_body']));
    }
}
