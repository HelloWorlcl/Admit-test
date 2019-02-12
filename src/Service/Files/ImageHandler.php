<?php

namespace App\Service\Files;

use App\Service\Files\Exceptions\NotAllowedFileExtensionException;

class ImageHandler extends FileHandler
{
    const ALLOWED_IMAGE_EXTENSIONS = ['image/jpeg', 'image/png'];

    public function upload(): void
    {
        $this->validateImage();
        parent::upload();
    }

    /**
     * @throws NotAllowedFileExtensionException
     */
    private function validateImage()
    {
        if (!$this->isAllowedExtension() || !$this->isAllowedMimeType()) {
            throw new NotAllowedFileExtensionException('Extension of the file is not allowed');
        }
    }

    private function isAllowedExtension(): bool
    {
        return in_array($this->file['type'], self::ALLOWED_IMAGE_EXTENSIONS);
    }

    private function isAllowedMimeType()
    {
        $imageInfo = getimagesize($this->file['tmp_name']);

        return in_array($imageInfo['mime'], self::ALLOWED_IMAGE_EXTENSIONS);
    }
}
