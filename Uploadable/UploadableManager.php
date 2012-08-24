<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Uploadable\UploadableListener;

class UploadableManager
{
    /** @var \Gedmo\Uploadable\UploadableListener */
    private $listener;
    private $fileInfoClass;

    public function __construct(UploadableListener $listener, $fileInfoClass = null)
    {
        $this->listener = $listener;

        if ($fileInfoClass && (!is_string($fileInfoClass) || !class_exists($fileInfoClass))) {
            $msg = 'Second argument must be a string containing a fully qualified class which implements FileInfoInterface.';

            throw new \InvalidArgumentException($msg);
        }

        $this->fileInfoClass = $fileInfoClass;
    }


    /**
     * This method marks an entity to be uploaded as soon as the "flush" method of your object manager is called.
     * After calling this method, the file info you passed is set for this entity in the listener. This is all it takes
     * to upload a file for an entity in the Uploadable extension.
     *
     * @param object $entity - The entity you are marking to "Upload" as soon as you call "flush".
     * @param mixed $fileInfo - The file info object or array. In Symfony 2, this will be typically an UploadedFile instance.
     */
    public function markEntityToUpload($entity, $fileInfo)
    {
        if (is_object($fileInfo) && $fileInfo instanceof UploadedFile) {
            $fileInfo = new $this->fileInfoClass($fileInfo);
        }

        $this->listener->addEntityFileInfo($entity, $fileInfo);
    }
}
