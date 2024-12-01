<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;
    protected $fromNumber;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
        $this->fromNumber = config('services.twilio.phone_number');
    }

    public function sendSMS($to, $message)
    {
        try {
            $this->client->messages->create(
                $to,
                [
                    'from' => $this->fromNumber,
                    'body' => $message
                ]
            );
            return true;
        } catch (\Exception $e) {
            \Log::error('Twilio SMS Error: ' . $e->getMessage());
            return false;
        }
    }
}
