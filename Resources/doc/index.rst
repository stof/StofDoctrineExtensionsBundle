Provides integration for DoctrineExtensions_ for your Symfony2 Project.

Features
========

This bundle allows to easily use DoctrineExtensions_ in your Symfony2
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
- Loggable - tracks your record changes and is able to manage versions.

All these extensions can be nested together. And most already use only
annotations without interface requirement to not to aggregate the
entity itself and has implemented proper caching for metadata.

See the official blog_ for more details.

Warning
=======

As the DoctrineExtensions library does not provide an XML driver, you
have to use either annotations or YAML for your mapping.
Setting a ``DriverChain`` implementation to load only the gedmo mapping
from annotations or YAML and the standard mapping from XML would require
hacking the way the ORM is configured by DoctrineBundle so it will never
be done in the bundle.

Installation
============

Add DoctrineExtensions to your vendor dir
-----------------------------------------

::

    git submodule add git://github.com/l3pp4rd/DoctrineExtensions.git vendor/gedmo-doctrine-extensions

Add DoctrineExtensionsBundle to your src/Bundle dir
---------------------------------------------------

::

    git submodule add git://github.com/stof/StofDoctrineExtensionsBundle.git vendor/bundles/Stof/DoctrineExtensionsBundle

Register the DoctrineExtensions and Stof namespaces
---------------------------------------------------

::

    // app/autoload.php
    $loader->registerNamespaces(array(
        'Stof'  => __DIR__.'/../vendor/bundles',
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

.. note::

    This is not needed if you use the auto_mapping setting.

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
    doctrine_mongodb:
        document_managers:
            default:
                mappings:
                    StofDoctrineExtensionsBundle: ~
                    # ... your others bundle

.. note::

    The mapping is only needed when using the ``Translatable`` or the
    ``Loggable`` behaviors. If you don't use any of them, you can disable
    it to avoid creating the tables even when using auto_mapping::

        doctrine:
            orm:
                auto_mapping: true
                mappings:
                    StofDoctrineExtensionsBundle: false

Configure the bundle
====================

You have to activate the extensions for each entity manager for which
you want to enable the extensions. The id is the id of the DBAL
connection when using the ORM behaviors. It is the id of the document
manager when using mongoDB.

This bundle needs a default locale used if the translation does not
exists in the asked language. If you don't provide it explicitly, it
will default to ``en``.

in YAML::

    # app/config.yml
    stof_doctrine_extensions:
        default_locale: en_US
        orm:
            default: ~
        mongodb:
            default: ~

or in XML::

    <!-- app/config.xml -->
    <container xmlns:stof_doctrine_extensions="http://symfony.com/schema/dic/stof_doctrine_extensions">
        <stof_doctrine_extensions:config default-locale="en_US">
            <stof_doctrine_extensions:orm>
                <stof_doctrine_extensions:entity-manager id="default" />
            </stof_doctrine_extensions:orm>
            <stof_doctrine_extensions:mongodb>
                <stof_doctrine_extensions:document-manager id="default" />
            </stof_doctrine_extensions:mongodb>
        </stof_doctrine_extensions:config>
    </container>

Activate the extensions you want
================================

By default the bundle does not attach any listener.
For each of your entity manager, declare the extensions you want to enable::

    # app/config.yml
    stof_doctrine_extensions:
        default_locale: en_US
        orm:
            default:
                tree: true
                timestampable: false # not needed: listeners are not enabled by default
            other:
                timestampable: true

or in XML::

    <!-- app/config.xml -->
    <container xmlns:doctrine_extensions="http://symfony.com/schema/dic/stof_doctrine_extensions">
        <stof_doctrine_extensions:config default-locale="en_US">
            <stof_doctrine_extensions:orm>
                <stof_doctrine_extensions:entity-manager
                    id="default"
                    tree="true"
                    timestampable="false"
                />
                <stof_doctrine_extensions:entity-manager
                    id="other"
                    timestampable="true"
                />
            </stof_doctrine_extensions:orm>
        </stof_doctrine_extensions:config>
    </container>

Same is available for MongoDB using ``document-manager`` in the XML
files instead of ``entity-manager``.

.. caution::

    If you configure the listeners of an entity manager in several
    config file the last one will be used. So you have to list all the
    listeners you want to detach.

Use the DoctrineExtensions library
==================================

All explanations about this library are available on the official blog_

As bundle uses the new annotation implementation (as all Symfony2 code)
the annotations are a bit different.

Instead of::

    /**
     * @gedmo:Tree
     */

use::

    use Gedmo\Mapping\Annotation as Gedmo;
    /**
     * @Gedmo\Tree
     */

This applies for all annotations of the library.

The default entity for translations is
``Stof\DoctrineExtensionsBundle\Entity\Translation``. The default
document is ``Stof\DoctrineExtensionsBundle\Document\Translation``.

Creating your own translation entity
------------------------------------

When you have a great number of entries for an entity you should create
a dedicated translation entity to have good performances. The only
difference when using it with Symfony2 is the mapped-superclass to use.

The simplest way to do it is to copy the default translation entity
and just change the namespace and the class name.

Here is an example for the ORM::

    // src/Application/MyBundle/Entity/MyTranslationEntity.php

    namespace Application\MyBundle\Entity;

    use Stof\DoctrineExtensionsBundle\Entity\AbstractTranslation;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * Application\MyBundle\Entity\MyTranslationEntity
     *
     * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
     * @ORM\Table(
     *         name="ext_translations",
     *         indexes={@ORM\index(name="translations_lookup_idx", columns={
     *             "locale", "object_class", "foreign_key"
     *         })},
     *         uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
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

Overriding the listeners
------------------------

You can change the listeners used by extending the Gedmo listeners (or
the listeners of the bundle for translations) and giving the class name
in the configuration.

in YAML::

    # app/config.yml
    stof_doctrine_extensions:
        class:
            tree:           MyBundle\TreeListener
            timestampable:  MyBundle\TimestampableListener
            sluggable:      ~
            translatable:   ~
            loggable:       ~

or in XML::

    <!-- app/config.xml -->
    <container xmlns:doctrine_extensions="http://symfony.com/schema/dic/stof_doctrine_extensions">
        <stof_doctrine_extensions:config>
            <stof_doctrine_extensions:class
                tree="MyBundle\TreeListener"
                timestampable="MyBundle\TimestampableListener"
            />
        </stof_doctrine_extensions:config>
    </container>

.. _DoctrineExtensions: http://github.com/l3pp4rd/DoctrineExtensions
.. _blog:               http://gediminasm.org/articles
.. _documentation:      http://docs.symfony-reloaded.org/master/guides/doctrine/orm/overview.html
