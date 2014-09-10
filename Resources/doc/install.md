Installation
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
	"cp/terms-bundle": "dev-master",
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

Update your database

```
php app/console propel:migration:generate-diff
php app/console propel:migration:migrate
```

Add the following code to your routing configuration:

```yaml
cp_terms_bundle_admin:
    resource: "@CPTermsBundle/Resources/config/routing/admin.yml"

cp_terms_bundle_admin:
    resource: "@CPTermsBundle/Resources/config/routing/admin.yml"
```
