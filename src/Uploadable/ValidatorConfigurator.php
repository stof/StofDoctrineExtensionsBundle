<?php

namespace Stof\DoctrineExtensionsBundle\Uploadable;

use Gedmo\Uploadable\Mapping\Validator;

/**
 * @internal
 */
class ValidatorConfigurator
{
    /** @var bool */
    private $validateWritableDirectory;

    /**
     * @param bool $validateWritableDirectory
     */
    public function __construct($validateWritableDirectory)
    {
        $this->validateWritableDirectory = $validateWritableDirectory;
    }

    public function configure(): void
    {
        Validator::$validateWritableDirectory = $this->validateWritableDirectory;
    }
}
