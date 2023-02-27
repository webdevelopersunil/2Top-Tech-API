<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReferralEmail extends Mailable
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
        $data = [
            '{{referringToName}}'         =>  $this->details['name'],
            '{{restaurantOwnerName}}'          =>  $this->details['restaurant_name'],
        ];
        // dd($this->details);

        $emailTemplate = get_email_template($this->details['email_template']);

        $email_body = getFormattedEmailData($data, $emailTemplate->email_body);
        $subject = getFormattedEmailData($data, $emailTemplate->email_subject);

        return $this->to($this->details['email'])
            ->from(trim(config('mail.from.address')) )
            ->subject(trim($subject))
            ->view('emails.email')
            ->with('logo', '')
            ->with('email_body', trim($email_body));
    }
}
