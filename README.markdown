Provides integration for [DoctrineExtensions](http://github.com/l3pp4rd/DoctrineExtensions) for your Symfony2 Project.

## Features

- Tree - this extension automates the tree handling process and adds some tree specific functions on repository.
- Translatable - gives you a very handy solution for translating records into diferent languages. Easy to setup, easier to use.
- Sluggable - urlizes your specified fields into single unique slug
- Timestampable - updates date fields on create, update and even property change.

All these extensions can be nested together. And most allready use only annotations without interface requirement
to not to aggregate the entity itself and has implemented proper caching for metadata.

## Installation

### Add DoctrineExtensions to your vendor dir

    git submodule add git://github.com/l3pp4rd/DoctrineExtensions.git src/vendor/doctrine-extensions

### Register the DoctrineExtensions namespace

    // src/autoload.php
    $loader->registerNamespaces(array(
        'DoctrineExtensions' => $vendorDir.'/doctrine-extensions/lib',
        // your other namespaces
    ));

### Add DoctrineExtensionsBundle to your src/Bundle dir

    git submodule add git://github.com/stof/DoctrineUserBundle.git src/Bundle/DoctrineExtensionsBundle

### Add DoctrineExtensionsBundle to your application kernel

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Bundle\DoctrineExtensionsBundle\DoctrineExtensionsBundle(),
            // ...
        );
    }

## Use the DoctrineExtensions library

All explanations about this library is available [here](http://gediminasm.org "Tutorials for extensions")

The default entity for translations is Bundle\DoctrineExtensionsBundle\Entity\TranslationEntity

### Creating your own translation entity

When you have a great number of entries for an entity you should create a
dedicated translation entity to have good performances. This is very easy :

    // src/Application/MyBundle/Entity/MyTranslationEntity.php

    namespace Application\MyBundle\Entity;

    use Bundle\DoctrineExtensionsBundle\Entity\AbstractTranslationEntity

    /**
     * Application\MyBundle\Entity\MyTranslationEntity
     *
     * @orm:Entity
     * @orm:Table(name="my_translation")
     */
    class TranslationEntity extends AbstractTranslationEntity
    {
    }
