<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\JobPostNotifyMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Http\Service\PushNotificationService;

class JobPostEmailNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification_data = [
            'id'   => '1',
            'title' => 'New Technician',
            'type' => 'email',
            'message' => "<b>New Technician</b>A new Technician profile has been created.",
            "ios_badgeType"=>"Increase",
            "ios_badgeCount"=> 1
        ];

        (new PushNotificationService)->partRequestMailToAdmin($to,$user,$notification_data);

        Mail::queue(new JobPostNotifyMail($this->details));
    }
}
