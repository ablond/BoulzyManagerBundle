Usage
=====

Managers are a great way to hold your models logic. This bundle provides services
to use homogenous managers for objects managed by Doctrine.

Get a manager service for an object/class
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The best way to retrieve a manager is to use the manager factory. The manager factory
accepts both an instance of a class or the class name as parameter. If you registered
a custom manager for the (see section below), it will return this manager. If not,
it will check for a custom manager that supports the class. Finally, if none is
available, it will return a default manager, providing you methods to handle
CRUD operations on this class.

.. code-block:: php

    <?php

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface $container */
    $manager = $container->get(ManagerFactory::class)->getManager(MyEntity::class);
    
    // or:
    // $manager = $container->get(ManagerFactory::class)->getManager($myEntity);

    $myEntity = $manager->get(123); // Get an object by its Doctrine identifier

Usage of a manager
~~~~~~~~~~~~~~~~~~

- :code:`Boulzy\ManagerBundle\Manager\ManagerInterface::get($id);`: Gets an object by its Doctrine identifier.et of criteria.
- :code:`Boulzy\ManagerBundle\Manager\ManagerInterface::getAll();`: Gets all objects.
- :code:`Boulzy\ManagerBundle\Manager\ManagerInterface::getBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)`: Gets objects by a set of criteria.
- :code:`Boulzy\ManagerBundle\Manager\ManagerInterface::getOneBy(array $criteria);`: Gets an object by a set of criteria.
- :code:`Boulzy\ManagerBundle\Manager\ManagerInterface::create();`: Returns a new instance of the managed class.
- :code:`Boulzy\ManagerBundle\Manager\ManagerInterface::save($object);`: Saves an object.
- :code:`Boulzy\ManagerBundle\Manager\ManagerInterface::delete($object);`: Deletes an object.
- :code:`Boulzy\ManagerBundle\Manager\ManagerInterface::supports($class);`: Checks if the manager supports this class.
- :code:`Boulzy\ManagerBundle\Manager\ManagerInterface::getClass();`: Gets managed object class.
- :code:`Boulzy\ManagerBundle\Manager\Manager::getRepository($id);`: Gets the repository of the managed class.

Create a custom manager
~~~~~~~~~~~~~~~~~~~~~~~

If you need to add logic in your manager, you can create a custom manager and
tag the service for the manager factory to be aware of the manager.

The manager must be an implementation of the :code:`Boulzy\ManagerBundle\Manager\ManagerInterface`.
The :code:`Boulzy\ManagerBundle\Manager\Manager` abstract class makes it very easy
to create a custom manager.

Here is an example for a custom manager for a :code:`AppBundle\Entity\MyEntity`:

.. code-block:: php

    <?php
    // src/AppBundle/Manager/MyManager.php
    
    namespace AppBundle\Manager;

    use AppBundle\Entity\MyEntity;
    use Boulzy\ManagerBundle\Manager\Manager;

    class MyManager extends Manager
    {
        public function getClass(): string
        {
            return MyEntity::class;
        }
    }

.. code-block:: yaml

    # app/config/services.yml
    services:
        AppBundle\Manager\MyManager:
            arguments: ['@doctrine.orm.entity_manager']
            tags: 
                - { name: boulzy_manager.manager }

The :code:`Boulzy\ManagerBundle\Manager\Manager` takes an implementation of
:code:`Doctrine\Common\Persistence\ObjectManager`. Set the doctrine manager
according to the Doctrine driver you use.
