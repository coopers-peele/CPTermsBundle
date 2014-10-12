CPTermsBundle - Installation
=============

Requirements
------------

* PHP > 5.3.3
* Symfony > 2.0
* Propel > 1.4

Installation
------------

Add the package to your project's `composer.json`:

```json
"require": {
	"cp/terms-bundle": "~1.0",
	…
}
```

Register the bundle in `AppKernel.php`:

```php
public function registerBundles()
{
    bundles = (
        new CP\Bundle\TermsBundle\CPTermsBundle(),
    );

    return bundles();
}
```

Update your app's packages:

```
php composer.phar update
```

Build the Propel model:

```
php app/console propel:sql:build
```

Update your database:

```
php app/console propel:migration:generate-diff
# Check the migration file first!
php app/console propel:migration:migrate
```

Update your routing configuration:

```yaml
# app/config/routing.yml

# admin routes
cp_terms_bundle_admin:
    resource: "@CPTermsBundle/Resources/config/routing/admin.yml"

# frontend routes
cp_terms_bundle_frontend:
    resource: "@CPTermsBundle/Resources/config/routing/frontend.yml"
```

Next steps
----------

You can now:

* read more about the concepts here: [General concepts](concepts.md)
* learn how to create your first terms of service: [Administration](admin.md)
