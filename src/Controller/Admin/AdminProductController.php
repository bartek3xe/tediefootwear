<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Enum\LanguageEnum;
use App\Exception\NotFoundException;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FileService;
use App\Service\Handler\ProductHandler;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product', name: 'app_admin_')]
class AdminProductController extends AbstractController
{
    public function __construct(
        private readonly ProductHandler $handler,
        private readonly FileService $fileService,
        private readonly ProductService $service,
    ) {
    }

    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, ['edition' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->handleForm($form, $product);

            return $this->redirectToRoute('app_admin_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/new.html.twig', [
            'form' => $form->createView(),
            'languages' => LanguageEnum::cases(),
        ]);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->handler->prepareData(
            $this->createForm(ProductType::class, $product),
            $product,
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->handleForm($form, $product);

            return $this->redirectToRoute('app_admin_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'languages' => LanguageEnum::cases(),
            'productFiles' => $this->fileService->prepareFilesForTemplate($product),
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        try {
            $this->service->delete($id);
        } catch (NotFoundException $exception) {
            return new Response('error', Response::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            return new Response('error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->redirectToRoute('app_admin_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
