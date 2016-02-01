Configuration
=============

You have to activate the extensions for each entity manager for which you want
to enable the extensions. The ``id`` is the ``id`` of the DBAL connection when
using the ORM behaviors. It is the ``id`` of the document manager when using
mongoDB.

This bundle needs a default locale used if the translation does not exists in
the asked language. If you don't provide it explicitly, it will default to
``en``.


.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        stof_doctrine_extensions:
            default_locale: en_US
            orm:
                default: ~
            mongodb:
                default: ~

    .. code-block:: xml

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

Activate the needed extensions
------------------------------

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

Same is available for MongoDB using ``document-manager`` in the XML files
instead of ``entity-manager``.

.. caution::

    If you configure the listeners of an entity manager in several configuration
    files, the last one will be used. So you have to list all the listeners you
    want to detach.

Use the DoctrineExtensions library
----------------------------------

All explanations about this library are available on the official
`official DoctrineExtensions documentation`_.

.. _`official DoctrineExtensions documentation`: https://github.com/Atlantic18/DoctrineExtensions/tree/master/doc/
