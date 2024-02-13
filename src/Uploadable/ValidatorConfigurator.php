<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Gedmo\Uploadable\Mapping\Validator;

/**
 * @internal
 */
class ValidatorConfigurator
{
    private bool $validateWritableDirectory;

    public function __construct(bool $validateWritableDirectory)
    {
        $this->validateWritableDirectory = $validateWritableDirectory;
    }

    public function configure(): void
    {
        Validator::$validateWritableDirectory = $this->validateWritableDirectory;
    }
}
