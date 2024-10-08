<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecaptchaService
{
    public const string RECAPTCHA_VERIFICATION_SITE_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $siteKey,
        private readonly string $secretKey,
    ) {
    }

    public function verify(string $recaptchaResponse): bool
    {
        try {
            $response = $this->httpClient->request('POST', self::RECAPTCHA_VERIFICATION_SITE_URL, [
                'body' => [
                    'secret' => $this->secretKey,
                    'response' => $recaptchaResponse,
                    'remoteip' => $_SERVER['REMOTE_ADDR'],
                ],
            ]);

            $responseData = json_decode($response->getContent(), true);

            return isset($responseData['success'], $responseData['score'])
                && $responseData['success']
                && $responseData['score'] >= 0.5;
        } catch (
            ClientExceptionInterface|
            TransportExceptionInterface|
            ServerExceptionInterface|
            RedirectionExceptionInterface $e
        ) {
            return false;
        }
    }

    public function getRecaptchaSiteKey(): string
    {
        return $this->siteKey;
    }
}
