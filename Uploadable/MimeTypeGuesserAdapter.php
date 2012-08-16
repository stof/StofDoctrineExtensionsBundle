<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Gedmo\Uploadable\MimeType\MimeTypeGuesserInterface;

class MimeTypeGuesserAdapter implements MimeTypeGuesserInterface
{
    protected $guesser;

    public function __construct()
    {
        $this->guesser = MimeTypeGuesser::getInstance();
    }

    public function guess($filePath)
    {
        return $this->guesser->guess($filePath);
    }

}
