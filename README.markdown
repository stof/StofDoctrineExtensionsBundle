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

    # app/config.yml
    app.config:
        user:
            default_locale: en_US

or with XML

    <!-- app/config.xml -->
    <container>
        <app:config>
            <app:user default_locale="en_US">
        </app:config>
    </container>

### Activate the bundle

You have to activate the extensions for entity manager. The id is the one used
in the ORM configuration.

    # app/config.yml
    doctrine_extensions.config:
        default: ~

or with XML

    <!-- app/config.xml -->
    <container xmlns:doctrine_extensions="http://www.symfony-project.org/schema/dic/doctrine_extensions">
        <doctrine_extensions:config>
            <doctrine_extensions:entity-manager id="default" />
        </doctrine_extensionsconfig>
    </container>

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

### Advanced configuration

By default the bundle attachs all 4 listeners to the entity managers listed in
the configuration. You can change this behavior by disabling some of them
explicitely.

    # app/config.yml
    doctrine_extensions.config:
        default:
            tree: false
            timestampable: true # not needed: listeners are enabled by default
        other:
            timestampable: false

or with XML

    <!-- app/config.xml -->
    <container xmlns:doctrine_extensions="http://www.symfony-project.org/schema/dic/doctrine_extensions">
        <doctrine_extensions:config>
            <doctrine_extensions:entity-manager
                id="default"
                tree="false"
                timestampable="true"
            />
            <doctrine_extensions:entity-manager
                id="other"
                timestampable="false"
            />
        </doctrine_extensionsconfig>
    </container>

### Attaching and Removing listeners manually

You can manage the listener with the ListenerManager.

    $lm = $container->get('doctrine_extensions.listener_manager');

The ListenerManager provides method to attach and remove each listener.

    $lm->addTreeListener($em);
    $lm->addSluggableListener($em);
    $lm->addTimestampableListener($em);
    $lm->addTranslationListener($em);

    $lm->removeTreeListener($em)
    $lm->removeSluggableListener($em)
    $lm->removeTimestampableListener($em)
    $lm->removeTranslationListener($em)

You can also attach or detach or the listeners:

    $lm->addAllListeners($em);
    $lm->removeAllListeners($em);
