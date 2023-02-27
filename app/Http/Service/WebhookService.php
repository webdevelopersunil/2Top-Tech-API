<?php

namespace App\Http\Service;

use Carbon\Carbon;

class WebhookService
{
    public function index()
    {

        // webhook.php
        //
        // Use this sample code to handle webhook events in your integration.
        //
        // 1) Paste this code into a new file (webhook.php)
        //
        // 2) Install dependencies
        //   composer require stripe/stripe-php
        //
        // 3) Run the server on http://localhost:4242
        //   php -S localhost:4242

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_00793ae9b8034a68a7f8c2c65111f6c5d1c6c8afe8b93796d2da19c7e8bfd722';

        $payload = @file_get_contents('php://input');
        $sig_header = 'sk_test_51MNEHbSC4FPfVVepgXLSCceR2AtQuNLKHSC8h6wAsyLPRo2XxzOUAeYRKRWKoxZHqOvkerRXZKTg4XR0gKCUHcs4008x67y3FY';

        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'invoice.upcoming':
                $invoice = $event->data->object;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }
}
