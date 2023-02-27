<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserAdminMail;

class VerifyEmail extends VerifyEmailBase
{
//    use Queueable;

    // change as you want
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        Mail::to(env('ADMIN_EMAIL'))->send(new NewUserAdminMail($notifiable));

        return (new MailMessage)

            ->subject('2Top Tech email verification')
            ->greeting("Congratulations ". $notifiable->first_name  ." and Welcome!" )
            ->line('We are excited that you have joined the 2Top Tech team.')
            ->line('In order to help you maximize your experience, we recommend you to verify your email address. Click on link below button to verify your email address.')
            ->action(
                'Verify Email Address',
                $this->verificationUrl($notifiable)
            )
            ->line('Make it a great day!')
            ->salutation("Thankyou");

    }
}

?>
