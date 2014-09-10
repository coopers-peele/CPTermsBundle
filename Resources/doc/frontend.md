Frontend usage
==============

The bundle provides several built-in pages that are designed to be included in the frontend (i.e. user-visible) pages of your application. These pages include:

* display the current (latest) terms of use
* display the changes between the latest terms and the version previously agreed to by the user
* allow user to agree to the terms

Routing
-------
Add the `frontend.yml` routing configuration to your application.

```yaml
cp_terms_bundle:
    resource: "@CPTermsBundle/Resources/config/routing/frontend.yml"
```

By default, the routing configuration sets the prefix `/tos` for all frontend pages. You are free to change this by defining your own routing configuration instead of using the one supplied by default.

Allow users to agree terms
--------------------------

A user is typically required to agree to your application's terms of service. The bundle makes it easy to implement this feature.

Import config to app:
```yaml
# app/config/config.yml
imports:
    ...
    - { resource: @CPTermsBundle/Resources/config/config.yml }
```

Apply `tos` behaviour to user class.

```xml
<table name="user">
    <column name="id" ... />
    ...
    <behavior name="tos"></behavior>
</table>
```

Build the model:
```php
php app/console propel:model:build
```

Generate migration diff and migrate to database.
```php
...
```
Now on default `/tos/agree` contains agree page for frontend.

**Note**: To add agree terms while signing up. Look at [add agree at register (Sign up)](./Resources/doc/signup.md)
