Boulzy\ModelManagerBundle
=========================

[![Build Status](https://travis-ci.org/B0ulzy/BoulzyManagerBundle.svg?branch=master)](https://travis-ci.org/B0ulzy/BoulzyManagerBundle)
[![Latest Stable Version](https://poser.pugx.org/boulzy/manager-bundle/v/stable)](https://packagist.org/packages/boulzy/manager-bundle)
[![Total Downloads](https://poser.pugx.org/boulzy/manager-bundle/downloads)](https://packagist.org/packages/boulzy/manager-bundle)

The Boulzy\ModelManagerBundle provides an implementable base to handle the domain models logic in a Symfony application.
It ensures consistency in the way domain models logic is handled, reusability and ease the comprehension of the code along
the application.

Features:

- An interface to be implemented by the managers, presenting basic methods to handle the models
- A manager factory to easily retrieve a model manager
- The possibility to use a default manager, in case the basic methods are enough for some models
- A basic implementation using Doctrine as the models persistence layer
- Unit tested with continuous integration

Documentation
-------------

The source of the documentation is stored in the `Resources/doc` folder in this bundle, and is available on Github:

[Read the documentation][documentation]

Installation
------------

All the installation instructions are located in the documentation.

License
-------

This bundle is under the MIT license. See the complete license [in the bundle][license].

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker][issues].

  [documentation]: ./Resources/doc/index.md
  [license]: ./LICENSE
  [issues]: /issues
