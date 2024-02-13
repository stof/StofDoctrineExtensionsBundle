<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Uploadable\FileInfo\FileInfoInterface;
use Gedmo\Uploadable\UploadableListener;

class UploadableManager
{
    private UploadableListener $listener;
    /** @var class-string<FileInfoInterface> */
    private string $fileInfoClass;

    /**
     * @param class-string<FileInfoInterface> $fileInfoClass
     */
    public function __construct(UploadableListener $listener, string $fileInfoClass)
    {
        $this->listener = $listener;
        $this->fileInfoClass = $fileInfoClass;
    }

    /**
     * This method marks an entity to be uploaded as soon as the "flush" method of your object manager is called.
     * After calling this method, the file info you passed is set for this entity in the listener. This is all it takes
     * to upload a file for an entity in the Uploadable extension.
     *
     * @param object $entity   - The entity you are marking to "Upload" as soon as you call "flush".
     * @param mixed  $fileInfo - The file info object or array. In Symfony, this will be typically an UploadedFile instance.
     *
     * @return void
     */
    public function markEntityToUpload($entity, $fileInfo)
    {
        if (is_object($fileInfo) && $fileInfo instanceof UploadedFile) {
            $fileInfoClass = $this->fileInfoClass;

            $fileInfo = new $fileInfoClass($fileInfo);
        }

        $this->listener->addEntityFileInfo($entity, $fileInfo);
    }

    /**
     * @return \Gedmo\Uploadable\UploadableListener
     */
    public function getUploadableListener()
    {
        return $this->listener;
    }
}
