<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Gedmo\Uploadable\MimeType\MimeTypeGuesserInterface;
use Symfony\Component\Mime\MimeTypes;

class MimeTypeGuesserAdapter implements MimeTypeGuesserInterface
{
    public function guess($filePath)
    {
        return MimeTypes::getDefault()->guessMimeType($filePath);
    }

}
