<?php

namespace App\Services;

use App\Message\GenerateReport;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Twilio\Rest\Client;

class MessageHandler implements MessageHandlerInterface
{
    /** @var Client $twilio */
    private $twilio;

    /** @var string $fromNumber */
    private $fromNumber;

    public function __construct(Client $twilio, string $fromNumber)
    {
        $this->twilio = $twilio;
        $this->fromNumber = $fromNumber;
    }

    public function __invoke(GenerateReport $report)
    {
        $toName = $report->getFirstName();
        $toNumber = $report->getPhoneNumber();

        $this->twilio->messages->create($toNumber, [
            'from' => $this->fromNumber,
            'body' => "Hi $toName! Your report has begun processing"
        ]);

        sleep(random_int(10, 15));

        // Print in the console so we can confirm the message was handled correctly
        var_dump('Report generated!');

        $this->twilio->messages->create($toNumber, [
            'from' => $this->fromNumber,
            'body' => "Hi $toName! Your report has finished processing apsent"
        ]);
    }
}