Getting started with BoulzyManagerBundle
========================================

Prerequisites
-------------

This version of the bundle requires PHP 7.1 or higher and Symfony 3.4 or higher.

Installation
------------

Installation is done in two quick steps:

### Step 1: Download BoulzyManagerBundle using composer

Require the bundle with composer:

```
    $ composer require boulzy/manager-bundle
```

Composer will install the bundle to your project's ``vendor/boulzy/manager-bundle`` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
# For Symfony 3

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

# For Symfony 4

<?php
// config/bundles.php

return [
    // ...
    Boulzy\ManagerBundle\BoulzyManagerBundle::class => ['all' => true],
];

```

That's it!

Usage
-----

### Managers

All managers must implement the `Boulzy\ManagerBundle\Manager\ManagerInterface` and the following methods:

* `ManagerInterface::find($identifier)`: Returns an objects by its identifier
* `ManagerInterface::findAll()`: Returns all objects supported by the manager
* `ManagerInterface::findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)`: Returns
a collection of objects according to filters, sort parameters and pagination.
* `ManagerInterface::findOneBy(array $criteria)`: Returns an object according to filters
* `ManagerInterface::create($object)`: Persists a new object
* `ManagerInterface::update($object)`: Updates an object
* `ManagerInterface::delete($object)`: Deletes an object
* `ManagerInterface::getClass()`: Returns the class managed by the manager
* `ManagerInterface::supports($object)`: Checks if the parameter is managed by the manager

The `Boulzy\ManagerBundle\Manager\Manager` abstract class provides an implementation of these methods (except the
`ManagerInterface::getClass()` method, which should be defined in each implementation) by delegating the storage layer
to an adapter service.

It's not uncommon that some business logic has to be applied before or after operating CRUD operations on an object.
For these situations, the `create`, `update` and `delete` methods of the `Boulzy\ManagerBundle\Manager\Manager` will try
to call some other methods, if they are defined:

* `Manager::onPreCreate($object)`: Called when `Manager::create($object)` is executed, but before the object is persisted
* `Manager::onPostCreate($object)`: Called when `Manager::create($object)` is executed, after the object is persisted
* `Manager::onCreateFailed($object)`: Called when `Manager::create($object)` is executed but the persistence failed,
before the exception is thrown
* `Manager::onPreUpdate($object)`: Called when `Manager::update($object)` is executed, but before the object is updated
* `Manager::onPostUpdate($object)`: Called when `Manager::update($object)` is executed, after the object is updated
* `Manager::onUpdateFailed($object)`: Called when `Manager::update($object)` is executed but the update failed, before
the exception is thrown
* `Manager::onPreDelete($object)`: Called when `Manager::delete($object)` is executed, but before the object is deleted
* `Manager::onDeleteFailed($object)`: Called when `Manager::delete($object)` is executed but the delete failed,
before the exception is thrown


Here is an example of what an `App\Entity\User` model might look like using a Doctrine ORM adapter.

```php
<?php

namespace App\Manager;

use App\Entity\User;
use App\Mailer\UserMailer;
use Boulzy\ManagerBundle\Manager\Manager;
use Boulzy\ManagerBundle\Storage\Adapter\DoctrineOrmAdapter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserManager extends Manager
{
    /** @var UserPasswordEncoder */
    private $encoder;

    /** @var UserMailer */
    private $mailer;

    /**
     * UserManager constructor.
     * 
     * @param DoctrineOrmAdapter $storage
     * @param UserPasswordEncoder $encoder
     * @param UserMailer $mailer
     */
    public function __construct(DoctrineOrmAdapter $storage, UserPasswordEncoder $encoder, UserMailer $mailer)
    {
        parent::__construct($storage);

        $this->encoder = $encoder;
        $this->mailer = $mailer;
    }

    /** @inheritdoc */
    public function onPreCreate($object)
    {
        // User password will be encoded before it's created in database
        $this->encodePassword($object);
    }

    /** @inheritdoc */
    public function onPostCreate($object)
    {
        // A confirmation email will be sent if the user is successfully persisted
        $this->sendConfirmationEmail($object);
    }

    /** @inheritdoc */
    public function onPreUpdate($object)
    {
        // User password will be encoded before it's updated in database
        $this->encodePassword($object);
    }

    /**
     * Sends a confirmation email.
     * 
     * @param User $user
     */
    public function sendConfirmationEmail(User $user)
    {
        $this->mailer->sendConfirmation($user);
    }

    /**
     * Encodes the user password.
     *
     * @param User $user
     */
    private function encodePassword(User $user)
    {
        if (null === $user->getPlainPassword()) {
            return;
        }

        $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $user->eraseCredentials();
    }

    /** @inheritdoc */
    public function getClass(): string
    {
        return User::class;
    }
}
```

And its usage, for example in a controller:

```php
<?php

namespace App\Controller;

use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** User controller */
class UserController extends AbstractController
{
    /**
     * Registers a new user.
     * 
     * @param UserManager $userManager
     * @param Request $request
     * 
     * @return Response
     */
    public function register(UserManager $userManager, Request $request): Response
    {
        $user = new User();

        // handle your form

        /*
         * This will:
         * - Encode the user password and erase its clear credentials
         * - Persist the user in the database
         * - Send him an email to confirm its account creation
         */
        $userManager->create($user);

        // render the response
    }
}
```

### Storage adapters

Storage adapters allow to use the same managers code base but with different storage solutions. This bundle is provided
with a Doctrine ORM adapter, which is used by default, but manager services can be wired to use a different implementation,
for example for objects that use ElasticSearch, or Reddis to be stored.

The default storage adapter can be defined in the configuration if the entirety of the project uses a different storage
solution.

To create a new adapter, please check the `Boulzy\Manager\Storage\Adapter\DoctrineAdapter` implementation.

Configuration
-------------

```
boulzy_manager:
    default_storage_adapter: Boulzy\ManagerBundle\Storage\Adapter\DoctrineOrmAdapter                        # Service ID
```
