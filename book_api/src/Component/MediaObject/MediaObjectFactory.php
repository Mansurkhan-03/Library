<?php

declare(strict_types=1);

namespace App\Component\MediaObject;

use App\Entity\MediaObject;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

readonly class MediaObjectFactory
{
    public function __construct(
        private Filesystem $filesystem,
        private KernelInterface $kernel,
    ){}
    public function create(string $fileName, string $fileType, string $fileLocationPath): Mediaobject
    {
        $tempDir = sys_get_temp_dir();
        $originalFilePath = $this->kernel->getProjectDir() . $fileLocationPath
            . $fileName;
        $tempFilePath = $tempDir . '/' . $fileName;
        $this->filesystem->copy($originalFilePath, $tempFilePath, true);
        $file = new UploadedFile(
            $tempFilePath,
            explode('.', $fileName) [0],    // file.jpg"
            $fileType,                              //image/ong
            null,
            true
        );
        $media = new MediaObject();
        $media->file = $file;
        return $media;
    }
}
