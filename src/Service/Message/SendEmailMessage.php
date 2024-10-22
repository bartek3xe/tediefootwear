<?php

declare(strict_types=1);

namespace App\Service\Message;

use Symfony\Component\Mime\Email;

class SendEmailMessage
{
    private Email $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}
