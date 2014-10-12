CPTermsBundle - The `tos` behavior
==================================

In order to track who has agreed to your terms, and perhaps just as importantly, what version of your terms they have agreed to, some information has to be stored in the database.

Unfortunately, it is impossible for the CPTermsBundle to predict with any certainty which entity among those used by your app this agreement information should be associated with.

In most cases, you will want to associated this information with your user entity -- but which class this entity will be represented by is impossible for the CPTermsBundle to predict. There may also be cases where a single user may have several accounts for different apps, and each would require different terms, and therefore would require that the agreement of the terms is recorded on an account by account basis; in such a case, the User class, whatever that might be, would be unsuitable for our purpose.

The `tos` behavior
------------------

To resolve this, the CPTermsBundle provides a `tos` behavior. This is a [Propel](http://propelorm.org/) behavior, and at this point this feature of the CPTermsBundle will only work if the entity concerned is designed via Propel.

When set on a class, the `tos` behavior adds logic to that class that enables it to record the agreement to your terms of service.

Since this is a behavior, it can be applied to any class. If you are using the very popular [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle) to handle user management, then you can add this behavior to the `FOS\UserBundle\Propel\User` class provided by that bundle. We'll look at this case first, and then review some other options. 

To enable the `tos` behavior, you need to register it in the propel config first. You can do this easily by importing the bundle's `config.yml` in your app's config:

```yaml
# app/config/config.yml
imports:
    - { resource: parameters.yml }
    - { resource: security.yml }
    - { resource: @CPTermsBundle/Resources/config/config.yml }
```

Adding the `tos` behavior to the `FOS\User\Bundle\Propel\User` class
--------------------------------------------------------------------

The simplest way to use the bundle is to add the `tos` behavior to the `FOS\UserBundle\Propel\User` class.

If you have sub-classed that class already in your app, then simply add the `tos` behavior to your sub-class.

If you are using the original User class, then you need to override the original schema, as per the following procedure:

* copy the original `FOS\UserBundle\Resources\config\propel\schema.xml` schema to the directory of your choice in your app, or in one of your app's bundles.
* add the `package="vendor/friendsofsymfony/user-bundle/Propel/"` attribute to the `database` element
* add the `tos` behavior to the `fos_user` table

The `package` attribute tells Propel to generate the classes in the original directory (`vendor/friendsofsymfony/user-bundle/Propel`). Note that if you are using an older version of the FOSUserBundle (PSR-0), or if you are using a non-standard path for your vendor packages, then you should adjust the value of the `package` attribute accordingly.

Adding the `tos` behavior to a class other than the `FOS\User\Bundle\Propel\User` class
---------------------------------------------------------------------------------------

If your application is not using the `FOS\UserBundle\Propel\User` class, you can still use the `tos` behavior. Just add it to the class that is the most relevant and/or convenient. As mentioned earlier, this class must use the Propel ORM framework.

However, if that class is not the same class as the one returned by the `SecurityContext->getToken()->getUser` method, then you need an extra step, which is to explain to the CPTermsBundle how to find the entity on which the `tos` behavior has been applied to.

This function is performed by the `cp_terms.entity_finder` service. This service is always an instance of the `CP\Bundle\TermsBundle\Finder\EntityFinderInterface`. This interface defines the single method `findEntity`. The `CP\Bundle\TermsBundle\Finder\UserEntityFinder` class is a default implementation of this interface, and returns the current user (from the security context). If this default implementation is not appropriate, then you need to:

* create your own concrete implementation of the your own `EntityFinderInterface` class
* define a service based on that class
* add the name of this service as the value of the `cp_terms.service.entity_finder.service` configuration setting. 

```yaml
# app/configconfig.yml

cp_terms:
    entity_finfer:
        enabled: true
        service: @my.entity_finder.service
```

Adding the `tos` behavior to a Profile class
--------------------------------------------

If your application includes a `Profile` class associated with each `User` instance, then an alternative solution is to add the `tos` behavior to this `Profile` class. 

You still need to change the `cp_terms.entity_finder` service, but for this you can use the `UserProfileEntityFinder` implementation provided by the bundle. Simply adjust your configuration:

```yaml
# app/configconfig.yml

cp_terms:
    entity_finfer:
        enabled: true
        service: cp_terms.entity_finder.profile
```

Note that this implementation requires your app's `User` class to have a `getProfile` method.