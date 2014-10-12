Administration
=============

The bundle provides a complete set of administration pages to allow you to easily manage your terms.

Routing
-------

Add the `admin.yml` routing configuration to your application.

```yaml
# app/config/routing.yml
cp_terms_bundle_admin:
    resource: "@CPTermsBundle/Resources/config/routing/admin.yml"
```

By default, the routing configuration sets the prefix `/admin/tos` for all admin pages. You are free to change this by defining your own routing configuration instead of using the one supplied by default.

Security
--------

All admin pages have restricted access. Access is restricted to users who have the `ROLE_CP_TERMS` role.

Make sure that the appropriate users are given this role.

For example, if you use the FOSUserBundle, you can use its command to grant the `ROLE_CP_TERMS` role to a specific user:

```
php app/console fos:user:promote {username} ROLE_CP_TERMS
```

Operations
----------

The administration pages allow you to perform the following operations:

* create terms
* clone terms
* edit terms
* finalize terms
