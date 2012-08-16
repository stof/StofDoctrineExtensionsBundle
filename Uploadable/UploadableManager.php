<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Uploadable\UploadableListener;

class UploadableManager
{
    /** @var \Gedmo\Uploadable\UploadableListener */
    private $listener;

    public function __construct(UploadableListener $listener)
    {
        $this->listener = $listener;
    }

    public function markEntityToUpload($entity, $fileInfo)
    {
        if (is_object($fileInfo) && $fileInfo instanceof UploadedFile) {
            $fileInfo = new UploadedFileInfo($fileInfo);
        }

        $this->listener->addEntityFileInfo($entity, $fileInfo);
    }
}
