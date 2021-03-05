SoftDeleteable Filter
=====================

If you want to use the SoftDeleteable behavior, you have to enable the
Doctrine filter.

.. code-block:: yaml

    # app/config/config.yml
    # (or config/packages/doctrine.yaml if you use Flex)
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
disable the filter. Here is an example::

    $filters = $em->getFilters();
    $filters->disable('softdeleteable');
