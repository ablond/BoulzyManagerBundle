BoulzyManagerBundle
===================

The BoulzyManagerBundle provides a base to implement in order to homogenize your
models logic. It also provides a basic implementation for Doctrine managed models.

Features include:
- A manager factory to retrieve a manager just by the name or an instance of the managed class
- A default doctrine manager for your doctrine related models (support for CouchDB and MongoDB documents coming soon)
- The possibility to configure the default manager to replace it by your own implementation of ManagerInterface
- Unit tested

Documentation
-------------

The source of the documentation is stored in the `Resources/doc/` folder in this bundle, and available on github.com:

[Read the documentation for master][documentation]

Installation
------------

All the installation instructions are located in the documentation.

License
-------

This bundle is under the MIT license. See the complete license [in the bundle][license].

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker][issues].

TODO
----

Here is the list of planned improvements:

- Ease configuration of the bundle
- Test the dependency injection and configuration part
- Test the case where an object can be managed by two managers without the possibility
to define a priority
- Write full documentation
- Release 1.0.0


  [documentation]: https://github.com/B0ulzy/BoulzyManagerBundle/tree/master/Resources/doc/index.rst
  [license]: https://github.com/B0ulzy/BoulzyManagerBundle/tree/master/LICENSE
  [issues]: https://github.com/B0ulzy/BoulzyManagerBundle/issues
