Configuration
=============

Add the extensions to your mapping
----------------------------------

Some of the extensions use their own entities to do their work. You need
to register their mapping in Doctrine when you want to use them.

.. code-block:: yaml

    # app/config/config.yml
    # (or config/packages/doctrine.yaml if you use Flex)
    doctrine:
        orm:
            entity_managers:
                default:
                    mappings:
                        gedmo_translatable:
                            type: annotation
                            prefix: Gedmo\Translatable\Entity
                            dir: "%kernel.project_dir%/vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity"
                            alias: GedmoTranslatable # (optional) it will default to the name set for the mapping
                            is_bundle: false
                        gedmo_translator:
                            type: annotation
                            prefix: Gedmo\Translator\Entity
                            dir: "%kernel.project_dir%/vendor/gedmo/doctrine-extensions/lib/Gedmo/Translator/Entity"
                            alias: GedmoTranslator # (optional) it will default to the name set for the mapping
                            is_bundle: false
                        gedmo_loggable:
                            type: annotation
                            prefix: Gedmo\Loggable\Entity
                            dir: "%kernel.project_dir%/vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity"
                            alias: GedmoLoggable # (optional) it will default to the name set for the mapping
                            is_bundle: false
                        gedmo_tree:
                            type: annotation
                            prefix: Gedmo\Tree\Entity
                            dir: "%kernel.project_dir%/vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity"
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

Configure the entity managers
-----------------------------

You have to activate the extensions for each entity manager for which you want
to enable the extensions. The id is the id of the DBAL connection when using the
ORM behaviors. It is the id of the document manager when using mongoDB.

This bundle needs a default locale used if the translation does not exists in
the asked language. If you don't provide it explicitly, it will default to
``en``.

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        # (or config/packages/stof_doctrine_extensions.yaml if you use Flex)
        stof_doctrine_extensions:
            default_locale: en_US

            # Only used if you activated the Uploadable extension
            uploadable:
                # Default file path: This is one of the three ways you can configure the path for the Uploadable extension
                default_file_path:       "%kernel.project_dir%/public/uploads"

                # Mime type guesser class: Optional. By default, we provide an adapter for the one present in the Mime component of Symfony
                mime_type_guesser_class: Stof\DoctrineExtensionsBundle\Uploadable\MimeTypeGuesserAdapter

                # Default file info class implementing FileInfoInterface: Optional. By default we provide a class which is prepared to receive an UploadedFile instance.
                default_file_info_class: Stof\DoctrineExtensionsBundle\Uploadable\UploadedFileInfo
            orm:
                default: ~
            mongodb:
                default: ~

    .. code-block:: xml

        <!-- app/config/config.xml -->
        <!-- (or config/packages/stof_doctrine_extensions.yaml if you use Flex) -->
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
        # (or config/packages/stof_doctrine_extensions.yaml if you use Flex)
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
        <!-- (or config/packages/stof_doctrine_extensions.yaml if you use Flex) -->
        <container xmlns:stof-doctrine_extensions="http://example.org/schema/dic/stof_doctrine_extensions">
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

.. _`DoctrineExtensions documentation`: https://github.com/Atlantic18/DoctrineExtensions/tree/v2.4.x/doc
