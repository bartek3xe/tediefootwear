<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ProductCategory;
use App\Enum\LanguageEnum;
use App\Exception\NotFoundException;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use App\Service\Handler\ProductCategoryHandler;
use App\Service\ProductCategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product/category', name: 'app_admin_')]
class AdminProductCategoryController extends AbstractController
{
    public function __construct(
        private readonly ProductCategoryHandler $handler,
        private readonly ProductCategoryService $service,
    ) {
    }

    #[Route('/', name: 'product_category_index', methods: ['GET'])]
    public function index(ProductCategoryRepository $productCategoryRepository): Response
    {
        return $this->render('admin/product_category/index.html.twig', [
            'product_categories' => $productCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'product_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $productCategory = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->handleForm($form, $productCategory);

            return $this->redirectToRoute('app_admin_product_category_index');
        }

        return $this->render('admin/product_category/new.html.twig', [
            'form' => $form->createView(),
            'languages' => LanguageEnum::cases(),
        ]);
    }

    #[Route('/{id}', name: 'product_category_show', methods: ['GET'])]
    public function show(ProductCategory $productCategory): Response
    {
        return $this->render('admin/product_category/show.html.twig', [
            'product_category' => $productCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductCategory $productCategory): Response
    {
        $form = $this->handler->prepareData(
            $this->createForm(ProductCategoryType::class),
            $productCategory,
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->handleForm($form, $productCategory);

            return $this->redirectToRoute('app_admin_product_category_index');
        }

        return $this->render('admin/product_category/edit.html.twig', [
            'product_category' => $productCategory,
            'form' => $form->createView(),
            'languages' => LanguageEnum::cases(),
        ]);
    }

    #[Route('/{id}', name: 'product_category_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        try {
            $this->service->delete($id);
        } catch (NotFoundException $exception) {
            return new Response('error ' . $exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            return new Response('error ' . $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->redirectToRoute('app_admin_product_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
