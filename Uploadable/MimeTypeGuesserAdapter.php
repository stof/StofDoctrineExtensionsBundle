<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Gedmo\Uploadable\MimeType\MimeTypeGuesserInterface;

class MimeTypeGuesserAdapter implements MimeTypeGuesserInterface
{
    public function guess($filePath)
    {
        return MimeTypeGuesser::getInstance()->guess($filePath);
    }

}
