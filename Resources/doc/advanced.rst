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
        <container xmlns:doctrine_extensions="http://symfony.com/schema/dic/stof_doctrine_extensions">
            <stof_doctrine_extensions:config>
                <stof_doctrine_extensions:class
                    tree="MyBundle\TreeListener"
                    timestampable="MyBundle\TimestampableListener"
                />
            </stof_doctrine_extensions:config>
        </container>
