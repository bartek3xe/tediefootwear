<?php

declare(strict_types=1);

namespace App\Service;

use App\Message\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly string $contactEmail,
    ) {
    }

    public function sendContactUsData(array $data): void
    {
        $email = (new Email())
            ->from($data['email'])
            ->to($this->contactEmail)
            ->subject($data['subject'])
            ->text('From: ' . $data['email'] . "\n\n" . $data['body']);

        $this->bus->dispatch(new SendEmailMessage($email));
    }
}
