Custom default manager
======================

If you wish to use your own default manager to add common logic to all your managers,
you can use a custom manager as a default manager. To do that, your manager must
use the :code:`Boulzy\ManagerBundle\Manager\DefaultManagerTrait` and configure the
bundle.

.. code-block:: php

    <?php
    // src/AppBundle/Manager/MyManager.php
    
    namespace AppBundle\Manager;

    use AppBundle\Entity\MyEntity;
    use Boulzy\ManagerBundle\Manager\DefaultManagerTrait;
    use Boulzy\ManagerBundle\Manager\Manager;

    class MyDefaultManager extends Manager
    {
        use DefaultManagerTrait;
    }

.. code-block:: yaml

    boulzy_manager:
        default_manager: AppBundle\Manager\MyDefaultManager
