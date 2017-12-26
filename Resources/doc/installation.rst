Installation
============

Installation Using Symfony Flex
-------------------------------

`Symfony Flex`_ is the new way to manage dependencies on Symfony 3.4 and higher
applications. If your project already uses Symfony Flex, execute this command to
download, register and configure the bundle automatically:

.. code-block:: terminal

    $ composer require stof/doctrine-extensions-bundle

That's all! You can skip the rest of this article.

Installation without Symfony Flex
---------------------------------

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

.. _`Symfony Flex`: https://symfony.com/doc/current/setup/flex.html
.. _`installation chapter`: https://getcomposer.org/doc/00-intro.md
