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
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(name: 'app_')]
class ContactController extends AbstractController
{
    public function __construct(
        private readonly MailerService $mailerService,
        private readonly RecaptchaService $recaptchaService,
        private readonly TranslatorInterface $translator,
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
            $recaptchaResponse = $request->get('g-recaptcha-response');

            if ($this->recaptchaService->verify($recaptchaResponse)) {
                $this->mailerService->sendContactUsData($form->getData(), $mailer);

                $this->addFlash('success', $this->translator->trans('contact.success_message'));

                $form = $this->createForm(ContactType::class);
            } else {
                $this->addFlash('error', $this->translator->trans('contact.recaptcha_failed'));
            }
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', $this->translator->trans('contact.submission_problem'));
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'recaptcha_site_key' => $this->getParameter('recaptcha_site_key'),
        ]);
    }
}
