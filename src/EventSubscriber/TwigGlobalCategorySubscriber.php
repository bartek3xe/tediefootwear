<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Repository\ProductCategoryRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigGlobalCategorySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProductCategoryRepository $categoryRepository,
        private readonly Environment $twig,
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $categories = $this->categoryRepository->findAll();
        $this->twig->addGlobal('product_categories', $categories);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
