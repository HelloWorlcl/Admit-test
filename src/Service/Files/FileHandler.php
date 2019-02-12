<?php

namespace App\Service\Files;

use App\Service\Files\Exceptions\FileWasNotUploadedException;

class FileHandler
{
    /**
     * @var array
     */
    protected $file;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $uploadPath;

    /**
     * @var string
     */
    protected $uploadedFilePath;

    public function __construct(array $file, string $fileName = null, string $uploadPath = null)
    {
        $this->file = $file;
        $this->fileName = $fileName ?: basename($file['name']);
        $this->uploadPath = $uploadPath ?: getenv('UPLOAD_DIRECTORY');
    }

    public function setFile(array $file): FileHandler
    {
        $this->file = $file;

        return $this;
    }

    public function getFile(): array
    {
        return $this->file;
    }

    public function setFileName(string $fileName): FileHandler
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setUploadPath(string $uploadPath): FileHandler
    {
        $this->uploadPath = $uploadPath;

        return $this;
    }

    public function getUploadPath(): string
    {
        return $this->uploadPath;
    }

    /**
     * @throws FileWasNotUploadedException
     */
    public function upload(): void
    {
        if (!move_uploaded_file($this->file['tmp_name'], $this->uploadPath . $this->fileName)) {
            throw new FileWasNotUploadedException('Unable to upload the file: ' . $this->fileName);
        }

        $this->setUploadedFilePath($this->uploadPath . $this->fileName);
    }

    protected function setUploadedFilePath(string $filePath): void
    {
        $this->uploadedFilePath = $filePath;
    }

    public function getUploadedFilePath(): string
    {
        return $this->uploadedFilePath;
    }
}
