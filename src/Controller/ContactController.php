<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactType;
use App\Service\MailerService;
use App\Service\RecaptchaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_')]
class ContactController extends AbstractController
{
    public function __construct(
        private readonly MailerService $mailerService,
        private readonly RecaptchaService $recaptchaService,
    ) {
    }

    /**
     * @throws RuntimeException
     * @throws LogicException
     */
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recaptchaResponse = $form->get('recaptcha')->getData();

            if (!$this->recaptchaService->verify($recaptchaResponse)) {
                $this->addFlash('error', 'reCAPTCHA verification failed. Please try again.');

                return $this->redirectToRoute('app');
            }

            $this->mailerService->sendContactUsData($form->getData(), $mailer);

            $this->addFlash('success', 'Your message has been sent successfully! We will get back to you soon.');

            return $this->redirectToRoute('app');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'There was a problem with your submission. Please check the form for errors and try again.');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'recaptcha_site_key' => $this->getParameter('recaptcha_site_key'),
        ]);
    }
}
