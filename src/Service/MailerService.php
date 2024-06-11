<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private readonly string $contactEmail)
    {
    }

    public function sendContactUsData(array $data, MailerInterface $mailer): void
    {
        $email = (new Email())
            ->from($data['email'])
            ->to($this->contactEmail)
            ->subject($data['subject'])
            ->text('From: ' . $data['email'] . "\n\n" . $data['body']);

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            return;
        }
    }
}
