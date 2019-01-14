StofDoctrineExtensionsBundle
============================

This bundle provides integration for `DoctrineExtensions`_ in your Symfony
projects.

Features
--------

* **Tree** - this extension automates the tree handling process and adds some
  tree specific functions on repository. (``closure``, ``nestedset`` or ``materialized path``).
* **Translatable** - gives you a very handy solution for translating records
  into different languages. Easy to setup, easier to use.
* **Sluggable** - urlizes your specified fields into single unique slug
* **Timestampable** - updates date fields on create, update and even property change.
* **Blameable** - updates string or association fields on create, update and even
  property change with a user name resp. reference.
* **Loggable** - helps tracking changes and history of objects, also supports
  version management.
* **Sortable** - makes any document or entity sortable
* **Translator** - explicit way to handle translations
* **Softdeleteable** - allows to implicitly remove records
* **Uploadable** - provides file upload handling in entity fields
* **Reference Integrity** - provides reference integrity for MongoDB, supports
  ``nullify`` and ``restrict``.

All these extensions can be nested together. And most already use only
annotations without interface requirement to not to aggregate the entity itself
and has implemented proper caching for metadata.

See the official `official DoctrineExtensions documentation`_ for more details.

.. toctree::
    :maxdepth: 2

    installation
    configuration
    softdeletable-filter
    uploadable-extension
    advanced

.. _`DoctrineExtensions`: https://github.com/Atlantic18/DoctrineExtensions
.. _`official DoctrineExtensions documentation`: https://github.com/Atlantic18/DoctrineExtensions/tree/v2.4.x/doc
