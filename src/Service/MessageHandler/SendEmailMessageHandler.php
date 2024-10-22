<?php

declare(strict_types=1);

namespace App\Service\MessageHandler;

use App\Service\Message\SendEmailMessage;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendEmailMessageHandler
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function __invoke(SendEmailMessage $message): void
    {
        try {
            $this->mailer->send($message->getEmail());
        } catch (TransportExceptionInterface $e) {
            return;
        }
    }
}
