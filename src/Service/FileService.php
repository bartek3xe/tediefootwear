<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\File;
use App\Entity\Product;
use App\Repository\FileRepository;
use App\Service\Factory\FileFactory;
use Doctrine\Persistence\ManagerRegistry;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileService
{
    public const array ALLOWED_EXTENSIONS = ['jpeg', 'jpg', 'png'];

    public function __construct(
        private readonly string $uploadDirectory,
        private readonly FileRepository $fileRepository,
        private readonly ManagerRegistry $doctrine,
        private readonly FilesystemOperator $defaultStorage,
    ) {
    }

    public function getFileFromRequest(Request $request): ?UploadedFile
    {
        return $request->files->get('product')['images'][0] ?? null;
    }

    public function prepareAndSave(UploadedFile $file, Product $product): File
    {
        $preparedFile = $this->prepare($file, $product);
        $this->save($preparedFile);

        return $preparedFile;
    }

    public function save(File $file): bool
    {
        $this->fileRepository->save($file);

        return true;
    }

    public function prepare(UploadedFile $file, Product $product): File
    {
        $filesCount = $this->fileRepository->countFilesByProduct($product);
        $filename = sprintf('%s-%s', uniqid(), $file->getClientOriginalName());
        $fileSize = $file->getSize();
        $stream = fopen($file->getPathname(), 'r+');
        try {
            $this->defaultStorage->writeStream($filename, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        } catch (FilesystemException $e) {
            throw new \RuntimeException('File could not be saved: ' . $e->getMessage());
        }

        return FileFactory::create(
            $filename,
            '/uploads/' . $filename,
            $file->getClientOriginalName(),
            $fileSize,
            $this->getExtension($file),
            $filesCount,
            $product,
        );
    }

    public function getExtension(UploadedFile $file): ?string
    {
        $extension = null;
        if (preg_match('/\.([a-zA-Z0-9]+)$/', $file->getClientOriginalName(), $matches)) {
            $extension = $matches[1];
        }

        return $extension;
    }

    public function checkExtension(UploadedFile $file): bool
    {
        $extension = $this->getExtension($file);

        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }

    public function revertFile(string $filename): void
    {
        if (str_contains($filename, '../')) {
            throw new \InvalidArgumentException('Invalid file path');
        }

        $filePath = $this->uploadDirectory . '/' . $filename;
        $fileEntity = $this->fileRepository->findOneBy(['filename' => $filename]);

        if (!$fileEntity || !file_exists($filePath)) {
            throw new \RuntimeException('File not found');
        }

        try {
            $this->defaultStorage->delete($filename);
        } catch (FilesystemException $e) {
            throw new \RuntimeException('Could not delete file: ' . $e->getMessage());
        }

        $em = $this->doctrine->getManager();
        $em->remove($fileEntity);
        $em->flush();
    }

    /**
     * @throws FilesystemException
     */
    public function loadFile(string $filename): Response
    {
        if (str_contains($filename, '../')) {
            throw new \InvalidArgumentException('Invalid file path');
        }

        $fileContents = $this->defaultStorage->read($filename);

        return new Response($fileContents, Response::HTTP_OK, [
            'Content-Type' => $this->defaultStorage->mimeType($filename),
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function reorderFiles(Product $product, Request $request): void
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->doctrine->getManager();

        foreach ($data['order'] as $position => $filename) {
            $file = $this->fileRepository->findOneBy([
                'filename' => $filename,
                'product' => $product,
            ]);

            if ($file) {
                $file->setPosition($position);
                $em->persist($file);
            }
        }

        $em->flush();
    }

    public function prepareFilesForTemplate(Product $product): array
    {
        $files = $this->fileRepository->findBy(['product' => $product], ['position' => 'ASC']);
        $productFiles = [];

        /** @var File $file */
        foreach ($files as $file) {
            $productFiles[] = [
                'filename' => $file->getFilename(),
                'size' => $file->getSize(),
                'extension' => $file->getExtension(),
            ];
        }

        return $productFiles;
    }
}
