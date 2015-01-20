Installation
============

Step 1: Download the Bundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

.. code-block:: bash

    $ composer require stof/doctrine-extensions-bundle:"~1.1@dev"

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.

Step 2: Enable the Bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Then, enable the bundle by adding the following line in the ``app/AppKernel.php``
file of your project:

.. code-block:: php

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            );

            // ...
        }

        // ...
    }

Step 3: Add the extensions to your mapping
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Some of the extensions use their own entities to do their work. If you want to
use them, you must register their mapping in Doctrine:

.. code-block:: yaml

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
                            alias: GedmoTranslatable # (optional) it will default to the name set for the mapping
                            is_bundle: false
                        gedmo_translator:
                            type: annotation
                            prefix: Gedmo\Translator\Entity
                            dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translator/Entity"
                            alias: GedmoTranslator # (optional) it will default to the name set for the mapping
                            is_bundle: false
                        gedmo_loggable:
                            type: annotation
                            prefix: Gedmo\Loggable\Entity
                            dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity"
                            alias: GedmoLoggable # (optional) it will default to the name set for the mapping
                            is_bundle: false
                        gedmo_tree:
                            type: annotation
                            prefix: Gedmo\Tree\Entity
                            dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity"
                            alias: GedmoTree # (optional) it will default to the name set for the mapping
                            is_bundle: false

.. note::

    If you are using the short syntax for the ORM configuration, the ``mappings``
    key is directly under ``orm:``

.. note::

    If you are using several entity managers, take care to register the entities
    for the right ones.

.. note::

    The mapping for MongoDB is similar. The ODM documents are in the ``Document``
    subnamespace of each extension instead of ``Entity``.

.. _`installation chapter`: https://getcomposer.org/doc/00-intro.md
