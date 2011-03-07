Provides integration for DoctrineExtensions_ for your Symfony2 Project.

Features
========

This bundle allows to easyly use DoctrineExtensions_ in your Symfony2
project by configuring it through a ``ListenerManager`` and the DIC.

DoctrineExtensions's features
-----------------------------

- Tree - this extension automates the tree handling process and adds
  some tree specific functions on repository.
- Translatable - gives you a very handy solution for translating
  records into diferent languages. Easy to setup, easier to use.
- Sluggable - urlizes your specified fields into single unique slug
- Timestampable - updates date fields on create, update and even
  property change.

All these extensions can be nested together. And most allready use only
annotations without interface requirement to not to aggregate the
entity itself and has implemented proper caching for metadata.

See the official blog_ for more details.

Installation
============

Add DoctrineExtensions to your vendor dir
-----------------------------------------

::

    git submodule add git://github.com/l3pp4rd/DoctrineExtensions.git vendor/gedmo-doctrine-extensions

Add DoctrineExtensionsBundle to your src/Bundle dir
---------------------------------------------------

::

    git submodule add git://github.com/stof/DoctrineExtensionsBundle.git src/Stof/DoctrineExtensionsBundle

Register the DoctrineExtensions and Stof namespaces
---------------------------------------------------

::

    // app/autoload.php
    $loader->registerNamespaces(array(
        'Stof' => __DIR__.'/../src',
        'Gedmo' => __DIR__.'/../vendor/gedmo-doctrine-extensions/lib',
        // your other namespaces
    ));

Add DoctrineExtensionsBundle to your application kernel
-------------------------------------------------------

::

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            // ...
        );
    }

Add DoctrineExtensionsBundle to your mapping
--------------------------------------------

See the official documentation_ for details.

for ORM::

    # app/config.yml
    doctrine:
        orm:
            mappings:
                StofDoctrineExtensionsBundle: ~
                # ... your others bundle

or for MongoDB ODM::

    # app/config.yml
    doctrine_mongo_db:
        mappings:
            StofDoctrineExtensionsBundle: ~
            # ... your others bundle

.. Note::

    This is only necessary if you want to use the Translatable behavior.

Configure the bundle
====================

Register the default locale
---------------------------

This bundle uses the session default_locale as the default locale used
if the translation does not exists in the asked language. So you have
to define it in the configuration file (Symfony2 define it to ``en`` by
default).

in YAML::

    # app/config.yml
    framework:
        session:
            default_locale: en_US

or in XML::

    <!-- app/config.xml -->
    <container>
        <framework:config>
            <app:session default-locale="en_US">
        </app:config>
    </container>

Activate the bundle
-------------------

You have to activate the extensions for each entity manager for which
you want to enable the extensions. The id is the id of the DBAL
connection when using the ORM behaviors. It is the id of the document
manager when using mongoDB

in YAML::

    # app/config.yml
    stof_doctrine_extensions:
        orm:
            default: ~
        mongodb:
            default: ~

or in XML::

    <!-- app/config.xml -->
    <container xmlns:stof_doctrine_extensions="http://symfony.com/schema/dic/stof_doctrine_extensions">
        <stof_doctrine_extensions:config>
            <stof_doctrine_extensions:orm>
                <stof_doctrine_extensions:entity-manager id="default" />
            </stof_doctrine_extensions:orm>
            <stof_doctrine_extensions:mongodb>
                <stof_doctrine_extensions:document-manager id="default" />
            </stof_doctrine_extensions:mongodb>
        </stof_doctrine_extensions:config>
    </container>

Use the DoctrineExtensions library
==================================

All explanations about this library are available on the official blog_

The default entity for translations is
``Stof\DoctrineExtensionsBundle\Entity\Translation``. The default
document is ``Stof\DoctrineExtensionsBundle\Document\Translation``.

Creating your own translation entity
------------------------------------

When you have a great number of entries for an entity you should create
a dedicated translation entity to have good performances. The only
difference when using it with Symfony2 is the mapped-superclass to use.

The simpliest way to do it is to copy the default translation entity
and just change the namespace and the class name.

Here is an example for the ORM::

    // src/Application/MyBundle/Entity/MyTranslationEntity.php

    namespace Application\MyBundle\Entity;

    use Stof\DoctrineExtensionsBundle\Entity\AbstractTranslation

    /**
     * Application\MyBundle\Entity\MyTranslationEntity
     *
     * @orm:Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
     * @orm:Table(
     *         name="ext_translations",
     *         indexes={@orm:index(name="translations_lookup_idx", columns={
     *             "locale", "object_class", "foreign_key"
     *         })},
     *         uniqueConstraints={@orm:UniqueConstraint(name="lookup_unique_idx", columns={
     *             "locale", "object_class", "foreign_key", "field"
     *         })}
     * )
     */
    class TranslationEntity extends AbstractTranslation
    {
    }

Same is doable for the ODM.

You can also create your own repositoryClass by extending
``Gedmo\Translatable\Entity\Repository\TranslationRepository`` or
``Gedmo\Translatable\Document\Repository\TranslationRepository``

Advanced use
============

Advanced configuration
----------------------

By default the bundle attachs all 4 listeners to the entity managers
listed in the configuration. You can change this behavior by disabling
some of them explicitely.

in YAML::

    # app/config.yml
    stof_doctrine_extensions:
        orm:
            default:
                tree: false
                timestampable: true # not needed: listeners are enabled by default
            other:
                timestampable: false

or in XML::

    <!-- app/config.xml -->
    <container xmlns:doctrine_extensions="http://symfony.com/schema/dic/stof_doctrine_extensions">
        <stof_doctrine_extensions:config>
            <stof_doctrine_extensions:orm>
                <stof_doctrine_extensions:entity-manager
                    id="default"
                    tree="false"
                    timestampable="true"
                />
                <stof_doctrine_extensions:entity-manager
                    id="other"
                    timestampable="false"
                />
            </stof_doctrine_extensions:orm>
        </stof_doctrine_extensions:config>
    </container>

Same is available for MongoDB using ``document-manager`` in the XML
files instead of ``entity-manager``.

.. Caution::

    If you configure the listeners of an entity manager in several
    config file the last one will be used. So you have to list all the
    listeners you want to detach.

Overriding the listeners
------------------------

You can change the listeners used by extending the Gedmo listeners (or
the listeners of the bundle for translations) and giving the class name
in the configuration.

in YAML::

    # app/config.yml
    stof_doctrine_extensions:
        class:
            orm:
                tree:           MyBundle\TreeListener
                timestampable:  MyBundle\TimestampableListener
                sluggable:      ~
                translatable:   ~
            mongodb:
                sluggable:      MyBundle\SluggableListener

or in XML::

    <!-- app/config.xml -->
    <container xmlns:doctrine_extensions="http://symfony.com/schema/dic/stof_doctrine_extensions">
        <stof_doctrine_extensions:config>
            <stof_doctrine_extensions:class>
                <stof_doctrine_extensions:orm
                    tree="MyBundle\TreeListener"
                    timestampable="MyBundle\TimestampableListener"
                />
                <stof_doctrine_extensions:mongodb
                    sluggable="MyBundle\SluggableListener"
                />
            </stof_doctrine_extensions:class>
        </stof_doctrine_extensions:config>
    </container>

.. _DoctrineExtensions: http://github.com/l3pp4rd/DoctrineExtensions
.. _blog:               http://gediminasm.org/articles
.. _documentation:      http://docs.symfony-reloaded.org/master/guides/doctrine/orm/overview.html
