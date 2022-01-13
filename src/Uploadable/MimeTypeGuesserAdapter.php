<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Gedmo\Uploadable\MimeType\MimeTypeGuesserInterface;
use Symfony\Component\Mime\MimeTypes;

class MimeTypeGuesserAdapter implements MimeTypeGuesserInterface
{
    /**
     * @param $filePath
     * @return ?string
     */
    public function guess($filePath)
    {
        return MimeTypes::getDefault()->guessMimeType($filePath);
    }

}
