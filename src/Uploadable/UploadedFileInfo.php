<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Gedmo\Uploadable\FileInfo\FileInfoInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFileInfo implements FileInfoInterface
{
    /** @var UploadedFile */
    private $uploadedFile;

    public function __construct(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }

    /**
     * @return ?string
     */
    public function getTmpName()
    {
        return $this->uploadedFile->getPathname();
    }

    /**
     * @return ?string
     */
    public function getName()
    {
        return $this->uploadedFile->getClientOriginalName();
    }

    /**
     * @return int|false
     */
    public function getSize()
    {
        return $this->uploadedFile->getSize();
    }

    /**
     * @return ?string
     */
    public function getType()
    {
        return $this->uploadedFile->getMimeType();
    }

    /**
     * @return int
     */
    public function getError()
    {
        return $this->uploadedFile->getError();
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isUploadedFile()
    {
        return is_uploaded_file($this->uploadedFile->getPathname());
    }
}
