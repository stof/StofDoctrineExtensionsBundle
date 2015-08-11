StofDoctrineExtensionsBundle
============================

Provides integration for `DoctrineExtensions`_ for your Symfony2 Project.

Features
--------

This bundle allows to easily use `DoctrineExtensions`_ in your Symfony
project by configuring it through a ``ListenerManager`` and the DIC.

DoctrineExtensions's features
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

- **Tree** - this extension automates the tree handling process and adds some
  tree specific functions on repository. (**closure**, **nestedset** or **materialized path**)
- **Translatable** - gives you a very handy solution for translating records into
  different languages. Easy to setup, easier to use.
- **Sluggable** - *urlizes* your specified fields into single unique slug.
- **Timestampable** - updates date fields on create, update and even property
  change.
- **Blameable** - updates string or association fields on create, update and
  even property change with a user name resp. reference.
- **Loggable** - helps tracking changes and history of objects, also supports
  version management.
- **Sortable** - makes any document or entity sortable
- **Translator** - explicit way to handle translations
- **Softdeleteable** - allows to implicitly remove records
- **Uploadable** - provides file upload handling in entity fields
- **Reference Integrity** - provides reference integrity for MongoDB, supports
  ``nullify`` and ``restrict``.

All these extensions can be nested together. And most already use only
annotations without interface requirement to not to aggregate the entity itself
and has implemented proper caching for metadata.

See the official `DoctrineExtensions documentation`_ for more details.

Installation
------------

Step 1: Download the Bundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

.. code-block:: bash

    $ composer require stof/doctrine-extensions-bundle

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.

Step 2: Enable the Bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Then, enable the bundle by adding the following line in the ``app/AppKernel.php``
file of your project:

.. code-block:: php

    // app/AppKernel.php

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

Some of the extensions uses their own entities to do their work. You need
to register their mapping in Doctrine when you want to use them.

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
                            alias: GedmoLoggable # (optional) it will default to the name set for the mappingmapping
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

Enable the ``softdeleteable`` filter
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you want to use the SoftDeleteable behavior, you have to enable the
Doctrine filter.

.. code-block:: yaml

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

    If you are using the short syntax for the ORM configuration, the ``filters``
    key is directly under ``orm:``

.. note::

    If you are using several entity managers, take care to register the filter
    for the right ones.

To disable the behavior, e.g. for admin users who may see deleted items,
disable the filter. Here is an example:

.. code-block:: php

    $filters = $em->getFilters();
    $filters->disable('softdeleteable');

Using ``Uploadable`` extension
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you want to use the Uploadable extension, first read the documentation in
DoctrineExtensions. Once everything is ready, use the form component as usual.
Then, after you verify the form is valid, do the following:

.. code-block:: php

    $document = new Document();
    $form = $this->createFormBuilder($document)
        ->add('name')
        ->add('myFile')
        ->getForm()
    ;

    if ($this->getRequest()->getMethod() === 'POST') {
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($document);

            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            // Here, "getMyFile" returns the "UploadedFile" instance that the form bound in your $myFile property
            $uploadableManager->markEntityToUpload($document, $document->getMyFile());

            $em->flush();

            $this->redirect($this->generateUrl(...));
        }
    }

    return array('form' => $form->createView());

And that's it. The Uploadable extension handles the rest of the stuff. Remember
to read its documentation!

Configure the bundle
--------------------

You have to activate the extensions for each entity manager for which you want
to enable the extensions. The id is the id of the DBAL connection when using the
ORM behaviors. It is the id of the document manager when using mongoDB.

This bundle needs a default locale used if the translation does not exists in
the asked language. If you don't provide it explicitly, it will default to
``en``.

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        stof_doctrine_extensions:
            default_locale: en_US

            # Only used if you activated the Uploadable extension
            uploadable:
                # Default file path: This is one of the three ways you can configure the path for the Uploadable extension
                default_file_path:       %kernel.root_dir%/../web/uploads

                # Mime type guesser class: Optional. By default, we provide an adapter for the one present in the HttpFoundation component of Symfony
                mime_type_guesser_class: Stof\DoctrineExtensionsBundle\Uploadable\MimeTypeGuesserAdapter

                # Default file info class implementing FileInfoInterface: Optional. By default we provide a class which is prepared to receive an UploadedFile instance.
                default_file_info_class: Stof\DoctrineExtensionsBundle\Uploadable\UploadedFileInfo
            orm:
                default: ~
            mongodb:
                default: ~

    .. code-block:: xml

        <!-- app/config/config.xml -->
        <container xmlns:stof-doctrine-extensions="http://example.org/schema/dic/stof_doctrine_extensions">
            <stof-doctrine-extensions:config default-locale="en_US">
                <stof-doctrine-extensions:orm>
                    <stof-doctrine-extensions:entity-manager id="default" />
                </stof-doctrine-extensions:orm>
                <stof-doctrine-extensions:mongodb>
                    <stof-doctrine-extensions:document-manager id="default" />
                </stof-doctrine-extensions:mongodb>
            </stof-doctrine-extensions:config>
        </container>

Activate the extensions you want
--------------------------------

By default the bundle does not attach any listener. For each of your entity
manager, declare the extensions you want to enable:

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        stof_doctrine_extensions:
            default_locale: en_US
            orm:
                default:
                    tree: true
                    timestampable: false # not needed: listeners are not enabled by default
                other:
                    timestampable: true

    .. code-block:: xml

        <!-- app/config/config.xml -->
        <container xmlns:doctrine_extensions="http://example.org/schema/dic/stof_doctrine_extensions">
            <stof-doctrine-extensions:config default-locale="en_US">
                <stof-doctrine-extensions:orm>
                    <stof-doctrine-extensions:entity-manager
                        id="default"
                        tree="true"
                        timestampable="false"
                    />
                    <stof-doctrine-extensions:entity-manager
                        id="other"
                        timestampable="true"
                    />
                </stof-doctrine-extensions:orm>
            </stof-doctrine-extensions:config>
        </container>

Same is available for MongoDB using ``document-manager`` in the XML files
instead of ``entity-manager``.

.. caution::

    If you configure the listeners of an entity manager in several configuration
    files, the last one will be used. So you have to list all the listeners you
    want to detach.

Use the DoctrineExtensions library
----------------------------------

All explanations about this library are available on the official
`DoctrineExtensions documentation`_.

Advanced use
------------

Overriding the listeners
~~~~~~~~~~~~~~~~~~~~~~~~

You can change the listeners used by extending the Gedmo listeners (or the
listeners of the bundle for translations) and giving the class name in the
configuration.

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        stof_doctrine_extensions:
            class:
                tree:           MyBundle\TreeListener
                timestampable:  MyBundle\TimestampableListener
                blameable:      ~
                sluggable:      ~
                translatable:   ~
                loggable:       ~
                softdeleteable: ~
                uploadable:     ~

    .. code-block:: xml

        <!-- app/config/config.xml -->
        <container xmlns:doctrine_extensions="http://example.org/schema/dic/stof_doctrine_extensions">
            <stof-doctrine-extensions:config>
                <stof-doctrine-extensions:class
                    tree="MyBundle\TreeListener"
                    timestampable="MyBundle\TimestampableListener"
                />
            </stof-doctrine-extensions:config>
        </container>

.. _`DoctrineExtensions`: https://github.com/Atlantic18/DoctrineExtensions
.. _`DoctrineExtensions documentation`: https://github.com/Atlantic18/DoctrineExtensions/tree/master/doc/
.. _`installation chapter`: https://getcomposer.org/doc/00-intro.md
