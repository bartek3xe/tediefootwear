<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\SendEmailMessage;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailMessageHandler
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
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
