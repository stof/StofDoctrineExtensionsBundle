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
        'Gedmo' => $vendorDir.'/doctrine-extensions/lib',
        // your other namespaces
    ));

### Add DoctrineExtensionsBundle to your src/Bundle dir

    git submodule add git://github.com/stof/DoctrineExtensionsBundle.git src/Bundle/DoctrineExtensionsBundle

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

## Configure the bundle

### Register the default locale

This bundle uses the session default_locale as the default locale used if the translation 
does not exists in the asked language. So you have to define it in the configuration file.

    <!-- app/config.xml -->
    <container>
        <app:config>
            <app:user default_locale="en_US">
        </app:config>
    </container>

or with yaml

    # app/config.yml
    app.config:
        user:
            default_locale: en_US

### Activate the update of the locale with the session

This allows to automatically use the locale of the session.
If this is not set it will always use the session default_locale.

    <!-- app/config.xml -->
    <container xmlns:doctrine_extensions="http://www.symfony-project.org/schema/dic/doctrine_extensions">
        <doctrine_extensions:config />
    </container>

or with yaml

    # app/config.yml
    doctrine_extensions.config: ~

## Use the DoctrineExtensions library

All explanations about this library is available [here](http://gediminasm.org "Tutorials for extensions")

The default entity for translations is Bundle\DoctrineExtensionsBundle\Entity\TranslationEntity

### Creating your own translation entity

When you have a great number of entries for an entity you should create a
dedicated translation entity to have good performances. This is very easy :

    // src/Application/MyBundle/Entity/MyTranslationEntity.php

    namespace Application\MyBundle\Entity;

    use Bundle\DoctrineExtensionsBundle\Entity\AbstractTranslation

    /**
     * Application\MyBundle\Entity\MyTranslationEntity
     *
     * @orm:Entity(repositoryClass="Gedmo\Translatable\Repository\TranslationRepository")
     * @orm:Table(name="my_translation", indexes={
     *      @orm:index(name="lookup_idx", columns={"locale", "entity", "foreign_key", "field"})
     * })
     */
    class TranslationEntity extends AbstractTranslation
    {
    }

You can also create your own repositoryClass by extending
Gedmo\Translatable\Repository\TranslationRepository

## Advanced use

The bundle automatically attach all 4 listeners to the default EntityManager. If
you want to use these listeners with another EntityManager you can attach them
with the following methods :

    DoctrineExtensionsBundle::addTreeListener($em)
    DoctrineExtensionsBundle::addSluggableListener($em)
    DoctrineExtensionsBundle::addTimestampableListener($em)
    DoctrineExtensionsBundle::addTranslationListener($em)

or all of them with the `DoctrineExtensionsBundle::addAllListeners` method

If you need to remove these listeners (eg if you need to force the createdAt
value) you can use the following methods :

    DoctrineExtensionsBundle::removeTreeListener($em)
    DoctrineExtensionsBundle::removeSluggableListener($em)
    DoctrineExtensionsBundle::removeTimestampableListener($em)
    DoctrineExtensionsBundle::removeTranslationListener($em)

or all of them with the `DoctrineExtensionsBundle::removeAllListeners` method

To change the locale of the used translation you can use the following method :

    DoctrineExtensionsBundle::setTranslatableLocale($locale)
