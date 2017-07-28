Getting started with BoulzyManagerBundle
========================================

Prerequisites
-------------

This version of the bundle requires PHP ^7.1, Symfony ^3.3 and DoctrineBundle ^1.6.

Installation
------------

Installation is done in three quick steps:

Step 1: Download BoulzyManagerBundle using composer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Require the bundle with composer:

.. code-block:: bash

    $ composer require boulzy/manager-bundle

Composer will install the bundle to your project's ``vendor/boulzy/manager-bundle``.
directory.

Step 2: Enable the bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Enable the bundle in the kernel:

.. code-block:: php

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

Step 3: Configure the bundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The bundle needs to know which Doctrine driver you use to define the right Doctrine
ObjectManager to use in the managers.

.. code-block:: yaml

    boulzy_manager:
        db_driver: orm  # Possible values: orm|mongodb|couchdb|phpcr


Next steps
----------

- Usage_
- `Custom default manager`_
- `Configuration reference`_


  .. _Usage: ./usage.rst
  .. _Custom default manager: ./custom-default-manager.rst
  .. _Configuration reference: ./configuration-reference.rst
