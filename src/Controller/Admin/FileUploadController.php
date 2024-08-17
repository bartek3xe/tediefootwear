<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileUploadController extends AbstractController
{
    public function __construct(
        private readonly FileService $fileService,
    ) {
    }

    #[Route('/upload/process/{id}', name: 'upload_process', methods: ['POST'])]
    public function process(Request $request, Product $product): JsonResponse
    {
        $file = $this->fileService->getFileFromRequest($request);

        if (!$file) {
            return new JsonResponse(['error' => 'No file provided'], Response::HTTP_BAD_REQUEST);
        }

        if (!$this->fileService->checkExtension($file)) {
            return new JsonResponse(['error' => 'Invalid file type'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $savedFile = $this->fileService->prepareAndSave($file, $product);
            $filename = $savedFile->getFilename();

            return new JsonResponse(['id' => $filename, 'filePath' => '/uploads/' . $filename], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Could not upload file'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/upload/revert/{filename}', name: 'upload_revert', methods: ['DELETE'])]
    public function revert(string $filename): JsonResponse
    {
        try {
            $this->fileService->revertFile($filename);
            return new JsonResponse(['status' => 'File deleted'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/upload/load/{filename}', name: 'upload_load', methods: ['GET'])]
    public function load(string $filename): Response
    {
        try {
            return $this->fileService->loadFile($filename);
        } catch (\Exception $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }

    #[Route('/upload/reorder/{id}', name: 'upload_reorder', methods: ['POST'])]
    public function reorder(Product $product, Request $request): JsonResponse
    {
        try {
            $this->fileService->reorderFiles($product, $request);
            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
