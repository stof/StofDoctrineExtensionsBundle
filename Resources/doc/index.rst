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

Bring in the vendor libraries
-----------------------------

This can be done in two different ways:

**Method #1**) Use composer

::

    "require": {
        "php": ">=5.3.2",
        "symfony/symfony": "2.1.*",
        "_comment":"your ther packages",

        "stof/doctrine-extensions-bundle":"dev-master",
    }


**Method #2**) Use git submodules

::

    git submodule add git://github.com/l3pp4rd/DoctrineExtensions.git vendor/gedmo-doctrine-extensions
    git submodule add git://github.com/stof/StofDoctrineExtensionsBundle.git vendor/bundles/Stof/DoctrineExtensionsBundle

Register the DoctrineExtensions and Stof namespaces
---------------------------------------------------

Only required, when using submodules.

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

Add the extensions to your mapping
----------------------------------

Some of the extensions uses their own entities to do their work. You need
to register their mapping in Doctrine when you want to use them.

::

    # app/config/config.yml
    doctrine:
        orm:
            entity_managers:
                default:
                    mappings:
                        gedmo_translatable:
                            type: annotation
                            prefix: Gedmo\Translatable\Entity
                            dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity"
                            alias: GedmoTranslatable # this one is optional and will default to the name set for the mapping
                        gedmo_translator:
                            type: annotation
                            prefix: Gedmo\Translator\Entity
                            dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translator/Entity"
                            alias: GedmoTranslator # this one is optional and will default to the name set for the mapping
                        gedmo_loggable:
                            type: annotation
                            prefix: Gedmo\Loggable\Entity
                            dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity"
                            alias: GedmoLoggable # this one is optional and will default to the name set for the mapping
                        gedmo_tree:
                            type: annotation
                            prefix: Gedmo\Tree\Entity
                            dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity"
                            alias: GedmoTree # this one is optional and will default to the name set for the mapping

.. note::

    If you are using the short syntax for the ORM configuration, the `mappings`
    key is directly under `orm:`

.. note::

    If you are using several entity managers, take care to register the entities
    for the right ones.

.. note::

    The mapping for MongoDB is similar. The ODM documents are in the `Document`
    subnamespace of each extension instead of `Entity`.

Enable the softdeleteable filter
--------------------------------

If you want to use the SoftDeleteable behavior, you have to enable the
doctrine filter.

::

    # app/config/config.yml
    doctrine:
        orm:
            entity_managers:
                default:
                    filters:
                        softdeleteable:
                            class: Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter
                            enabled: true

.. note::

    If you are using the short syntax for the ORM configuration, the `filters`
    key is directly under `orm:`

.. note::

    If you are using several entity managers, take care to register the filter
    for the right ones.

To disable the behaviour, e.g. for admin users who may see deleted items,
disable the filter. Here is an example::

    $filters = $em->getFilters();
    $filters->disable('softdeleteable');

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

    # app/config/config.yml
    stof_doctrine_extensions:
        default_locale: en_US
        orm:
            default: ~
        mongodb:
            default: ~

or in XML::

    <!-- app/config/config.xml -->
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

    # app/config/config.yml
    stof_doctrine_extensions:
        default_locale: en_US
        orm:
            default:
                tree: true
                timestampable: false # not needed: listeners are not enabled by default
            other:
                timestampable: true

or in XML::

    <!-- app/config/config.xml -->
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

Advanced use
============

Overriding the listeners
------------------------

You can change the listeners used by extending the Gedmo listeners (or
the listeners of the bundle for translations) and giving the class name
in the configuration.

in YAML::

    # app/config/config.yml
    stof_doctrine_extensions:
        class:
            tree:           MyBundle\TreeListener
            timestampable:  MyBundle\TimestampableListener
            sluggable:      ~
            translatable:   ~
            loggable:       ~
            softdeleteable: ~

or in XML::

    <!-- app/config/config.xml -->
    <container xmlns:doctrine_extensions="http://symfony.com/schema/dic/stof_doctrine_extensions">
        <stof_doctrine_extensions:config>
            <stof_doctrine_extensions:class
                tree="MyBundle\TreeListener"
                timestampable="MyBundle\TimestampableListener"
            />
        </stof_doctrine_extensions:config>
    </container>

.. _DoctrineExtensions: http://github.com/l3pp4rd/DoctrineExtensions
.. _blog:               http://gediminasm.org/
