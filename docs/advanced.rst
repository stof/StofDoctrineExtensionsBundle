Advanced Usage
==============

Overriding the listeners
------------------------

You can change the listeners used by extending the Gedmo listeners (or the
listeners of the bundle for translations) and giving the class name in the
configuration.

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        # (or config/packages/stof_doctrine_extensions.yaml if you use Flex)
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
        <!-- (or config/packages/stof_doctrine_extensions.yaml if you use Flex) -->
        <container xmlns:stof-doctrine_extensions="http://example.org/schema/dic/stof_doctrine_extensions">
            <stof-doctrine-extensions:config>
                <stof-doctrine-extensions:class
                    tree="MyBundle\TreeListener"
                    timestampable="MyBundle\TimestampableListener"
                />
            </stof-doctrine-extensions:config>
        </container>
