<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('email_templates')->delete();

        \DB::table('email_templates')->insert(array (
            array (
                'id' => 1,
                'name' => 'job_post_notify',
                'email_subject' => "Congratulations! You have posted a new job",
                'email_body' => "<p>Hi {{RO}},</p><p>Congratulations! You have successfully posted a job {{jobName}} for service {{serviceName}}.<p><p>Thank you</p>",

            ),
            array (
                'id' => 2,
                'name' => 'applied_job',
                'email_subject' => "Job Applicant (Provider)",
                'email_body' => "<h2>{{title}}</h2><p>{{body}}</p><p>{{thankyou}}</p>",

            ),
            array (
                'id' => 3,
                'name' => 'approved_decline_provider',
                'email_subject' => "Account has been {{status}}",
                'email_body' => "<p>Hi {{name}},</p><p>Your account has been {{status}}.</p><p>Thank you</p>",

            ),
            array (
                'id' => 4,
                'name' => 'Job_Offer_Accepted_from_RO',
                'email_subject' => "Job Offer Status",
                'email_body' => "<p>Hi {{restaurant}},<p>The Job offer {{job_id}}, extended to tech {{tech_name}} has been accepted by the technician.</p><p>Thank you</p>",

            ),
            array (
                'id' => 5,
                'name' => 'Job_Offer_Rejected_from_RO',
                'email_subject' => "Job Offer Status",
                'email_body' => "<p>Hi {{restaurant}},</p><p>The Job offer {{job_id}}, extended to tech {{tech_name}} has been declined by the technician.</p><p>Thank you</p>",

            ),
            array (
                'id' => 6,
                'name' => 'thankyou_email_after_verify_account',
                'email_subject' => "2Top Tech email verification confirmation",
                'email_body' => "<p>Congratulations {{name}} and Welcome!<p><p>Thankyou for verifying your email address. To complete your profile, please login to your account.</p><p>Make it a great day!</p><p>Thankyou</p>",

            ),
            array (
                'id' => 7,
                'name' => 'send_offer_to_provider',
                'email_subject' => "New job offer from {{RO}}",
                'email_body' => "<p>Hi {{name}},<p><p>The Job ({{job}}) you had applied for, has been accepted and the Restaurant has sent an offer.</p><p>Thankyou</p>",

            ),
            array (
                'id' => 8,
                'name' => 'provider_applying_job',
                'email_subject' => "New Job Application",
                'email_body' => "<p>Hi {{ro}},<p><p>You have a new Technician! {{technician}} has applied for Job {{job}} posted by you.</p><p>Thankyou</p>",

            ),
            array (
                'id' => 9,
                'name' => 'part_request_email',
                'email_subject' => "Part Request",
                'email_body' => "<p>Hi {{admin}},<p><p>Technician {{name}} has requested for a part.</p><p> Part Request detail </p><p>Thankyou</p>",

            ),
            array (
                'id' => 10,
                'name' => 'bank_detail_updated',
                'email_subject' => "Bank Detail Updated",
                'email_body' => "<p>Hi {{admin}},<p><p>Technician {{name}} has updated bank details.</p><p>Thankyou</p>",

            ),
            array (
                'id' => 11,
                'name' => 'new_job_post',
                'email_subject' => "New Job Post",
                'email_body' => "<p>Hi {{providerName}},<p><p>A new job post has been created which matches your skills ( {{serviceName}} ).</p><p>Thankyou</p>",

            ),
            array (
                'id' => 12,
                'name' => 'job_post_update_by_ro',
                'email_subject' => "Job Updated by RO",
                'email_body' => "<p>Hi {{providerName}},<p><p>The job post has been modified by the Restaurant.</p><p>Thankyou</p>",

            ), array (
                'id' => 13,
                'name' => 'referral_invitation',
                'email_subject' => "Inviting referrals for 2Top Tech",
                'email_body' => "<p>Hello {{referringToName}},</p><p>{{restaurantOwnerName}} has sent invitation for 2Top tech app.</p><p>Thankyou</p>",

            ), array (
                'id' => 14,
                'name' => 'job_rating',
                'email_subject' => "New Job rating for {{RO}}",
                'email_body' => "<p>Hello Admin,</p><p>The technician has given {{rate}} star rating to the company {{RO}}.</p><p>Thankyou</p>",

            ), array (
                'id' => 15,
                'name' => 'Job_completion_approve_and_payment',
                'email_subject' => "New Job rating for {{RO}}",
                'email_body' => "<p>Hello Admin,</p><p>The technician has given {{rate}} star rating for the job {{RO}}.</p><p>Thankyou</p>",

            ), array (
                'id' => 16,
                'name' => 'Job_completion_booking_invoice_provider',
                'email_subject' => "Job Completion Booking Invoice",
                'email_body' => "<p>Hello Admin, {{RO}}</p><p>The technician {{technicianName}} has completed and generated the Invoice for Job Service {{serviceName}}.</p><p>Thankyou</p>",

            ), array (
                'id' => 17,
                'name' => 'Cancellation_job_email_notification_to_technician',
                'email_subject' => "{{company_name}} cancelled booking",
                'email_body' => "<p>Hi {{provider_name}},<p><p>{{company_name}} cancelled booking.</p><p>You will be paid 1 hour for booking cancellation.</p><p>Thank you.</p>",
            ), array (
                'id' => 18,
                'name' => 'Cancellation_job_email_notification_to_restaurant',
                'email_subject' => "Your booking with {{provider_name}} is cancelled.",
                'email_body' => "<p>Hi {{company_name}},<p><p>Your booking with {{provider_name}} is cancelled.</p><p>Thank you.</p>",

            )
        ));
    }
}
