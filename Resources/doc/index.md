Getting started with BoulzyManagerBundle
========================================

Prerequisites
-------------

This version of the bundle requires PHP 7.1 or higher and Symfony 3.3 or higher.

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

That's it! You're now ready to use managers in your project.

Usage
-----

### Manager

The basic principle of this bundle is to provide an interface to be implemented by your model managers, so each managers
share the same basic methods. You should create one manager by model, and in each of this manager write the model logic.

An abstract class is provided for model whom persistence layer is handled by Doctrine.

If you want your manager to be available in the `ManagerCollection` (see below), you must add the `boulzy_manager.manager`
tag to its service definition. If you use autoconfiguration, all classes implementing the `Boulzy\Manager\ManagerInterface`
interface will be automatically tagged.

Here is an example of what an `App\Entity\User` model might look like using Doctrine ORM.

```php
    <?php

    namespace App\Manager;
    
    use App\Entity\User;
    use App\Mailer\UserMailer;
    use Boulzy\ManagerBundle\Manager\DoctrineManager;
    use Doctrine\ORM\EntityManager;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
    
    class UserManager extends DoctrineManager
    {
        private $encoder;
    
        private $mailer;
    
        public function __construct(EntityManager $om, UserPasswordEncoder $encoder, UserMailer $mailer)
        {
            parent::__construct($om);
            $this->encoder = $encoder;
            $this->mailer = $mailer;
        }
    
        public function onPreCreate($object)
        {
            $this->encodePassword($object);
        }
    
        public function onPostCreate($object)
        {
            $this->sendConfirmationEmail($object);
        }
    
        public function onPreUpdate($object)
        {
            $this->encodePassword($object);
        }
    
        public function sendConfirmationEmail(User $user)
        {
            $this->mailer->sendConfirmation($user);
        }
    
        public function getClass(): string
        {
            return User::class;
        }
    
        private function encodePassword(User $user)
        {
            $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->eraseCredentials();
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

    class UserController extends AbstractController
    {
        public function register(Request $request): Response
        {
            $user = new User();

            // handle your form

            $userManager = $this->get(UserManager::class);
            $userManager->create($user);

            // render the response
        }
    }

```

### ManagerCollection

The `Boulzy\ManagerBundle\Collection\ManagerCollection` service allows you to retrieve a manager supporting a model.
In that end, you must add the `boulzy_manager.manager` tag to its service definition. If you use autoconfiguration, all
classes implementing the `Boulzy\Manager\ManagerInterface` interface will be automatically tagged.

```php
    <?php

	// Model we don't know the class (from a Doctrine event for example)
	$model;

	$managerCollection = $container->get(\Boulzy\ManagerBundle\Collection\ManagerCollection::class);
	$manager = $managerCollection->get($model);
	$manager->doSomething($model);
```
