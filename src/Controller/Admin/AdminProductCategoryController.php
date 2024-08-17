<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ProductCategory;
use App\Enum\LanguageEnum;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product/category', name: 'app_admin_')]
class AdminProductCategoryController extends AbstractController
{
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
            $names = [];
            foreach (LanguageEnum::cases() as $language) {
                $fieldName = 'name_' . $language->value;
                $name = $form->get($fieldName)->getData();
                if ($name) {
                    $names[$language->value] = $name;
                }
            }

            $productCategory->setName($names);

            $entityManager->persist($productCategory);
            $entityManager->flush();

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
    public function edit(Request $request, ProductCategory $productCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        foreach (LanguageEnum::cases() as $language) {
            $form->get('name_' . $language->value)
                ->setData($productCategory->getName()[$language->value] ?? '');
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $names = [];
            foreach (LanguageEnum::cases() as $language) {
                $fieldName = 'name_' . $language->value;
                $name = $form->get($fieldName)->getData();
                if ($name) {
                    $names[$language->value] = $name;
                }
            }

            $productCategory->setName($names);

            $entityManager->persist($productCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_product_category_index');
        }

        return $this->render('admin/product_category/edit.html.twig', [
            'product_category' => $productCategory,
            'form' => $form,
            'languages' => LanguageEnum::cases(),
        ]);
    }

    #[Route('/{id}', name: 'product_category_delete', methods: ['POST'])]
    public function delete(Request $request, ProductCategory $productCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productCategory->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($productCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_product_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
