Getting started with BoulzyManagerBundle
========================================

The purpose of this bundle is to add Twig extensions providing helpful globals,
filters, etc...

Prerequisites
-------------

This version of the bundle requires PHP 7.0+, Symfony 3.3+ and DoctrineBundle 1.6+.
Note that you can use this bundle as a standalone library, without Doctrine and/or Symfony.
It loses a lot of its utility though.

Installation
------------

Installation is done in two quick steps:

Step 1: Download BoulzyManagerBundle using composer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Require the bundle with composer:

.. code-block:: bash

    $ composer require boulzy/manager-bundle "dev-master"

Composer will install the bundle to your project's ``vendor/boulzy/manager-bundle``.
directory.

Step 2: Enable the bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Enable the bundle in the kernel::

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Boulzy\ManagerBundle\BoulzyManagerBundle(),
            // ...
        );
    }

That's it! You're now ready to use managers in your project.

Usage
-----

Managers purpose is to handle your models logic without worrying about how your data
are retrieved.

If you're using Doctrine, you will fetch your data using repositories.
If you're using an API, you will probably have a SDK or have you own services to map
your models with the API endpoints.

Managers abstract this part to provide methods you can use globally without worrying
how your data are fetched. It also is a very good place to store your models logic.
