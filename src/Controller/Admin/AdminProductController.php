<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\File;
use App\Entity\Product;
use App\Enum\LanguageEnum;
use App\Form\ProductType;
use App\Repository\FileRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product', name: 'app_admin_')]
class AdminProductController extends AbstractController
{
    public function __construct(private readonly FileRepository $fileRepository)
    {
    }

    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, [
            'edition' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $names = [];
            $descriptions = [];
            foreach (LanguageEnum::cases() as $language) {
                $fieldName = 'name_' . $language->value;
                $fieldDesc = 'description_' . $language->value;
                $name = $form->get($fieldName)->getData();
                $description = $form->get($fieldDesc)->getData();
                if ($name) {
                    $names[$language->value] = $name;
                }
                if ($description) {
                    $descriptions[$language->value] = $description;
                }
            }

            $product->setName($names);
            $product->setDescription($descriptions);

            $entityManager->persist($product);
            $entityManager->flush();

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
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        foreach (LanguageEnum::cases() as $language) {
            $form->get('name_' . $language->value)
                ->setData($product->getName()[$language->value] ?? '');
            $form->get('description_' . $language->value)
                ->setData($product->getDescription()[$language->value] ?? '');
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $names = [];
            $descriptions = [];
            foreach (LanguageEnum::cases() as $language) {
                $fieldName = 'name_' . $language->value;
                $fieldDesc = 'description_' . $language->value;
                $name = $form->get($fieldName)->getData();
                $description = $form->get($fieldDesc)->getData();
                if ($name) {
                    $names[$language->value] = $name;
                }
                if ($description) {
                    $descriptions[$language->value] = $description;
                }
            }

            $product->setName($names);
            $product->setDescription($descriptions);

            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_product_index', [], Response::HTTP_SEE_OTHER);
        }

        $files = $this->fileRepository->findBy(['product' => $product], ['position' => 'ASC']);
        $productFiles = [];
        /**@var File $file*/
        foreach ($files as $file) {
            $productFiles[] = [
                'filename' => $file->getFilename(),
                'size' => $file->getSize(),
                'extension' => $file->getExtension(),
            ];
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'languages' => LanguageEnum::cases(),
            'productFiles' => $productFiles,
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
