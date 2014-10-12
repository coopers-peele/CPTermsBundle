Frontend usage
==============

The CPTermsBundle provides several elements to help you make use of your terms of service in your frontend pages (that is, the pages that are visible to your users).

Routing
-------

The CPTermsBundle provides you with the following 3 routes:

* `cp_terms` (default url: `/tos`): displays the latest terms
* `cp_terms_agree` (default url: `/tos/agree`): displays a page where the user can agree to your latest terms
* `cp_terms_diff` (default url: `/tos/diff`): displays and highlights the differences between the latest terms and the previous terms agreed to by the user

Add the `frontend.yml` routing configuration to your application.

```yaml
# app/config/routing.yml

cp_terms_bundle:
    resource: "@CPTermsBundle/Resources/config/routing/frontend.yml"
```

By default, the routing configuration sets the prefix `/tos` for all frontend pages. You are free to change this by defining your own routing configuration instead of using the one supplied by default.

Displaying the terms of service
------------------------------

The route named `cp_tersm` (default url: `/tos`) points to the page that displays your latest terms.

At any time, only the latest finalized terms are displayed. 

Terms finalized at an earlier date are never displayed.

Non-finalized terms are never displayed.

Allowing users to agree to terms
--------------------------------

As seen in [General concepts](concepts.md), in general the user's agreement to your terms is best collected at the time a new user opens an account (during the process called either registration or signup). This is also possible with the CPTermsBundle (see [Signup](Resources/doc/signup.md)).

However there are several scenarios where you will need to obtain the agreement of a user who is already registered, such as:

* you have published a revised version of your terms of service, and need to obtain the renewed agreement of the user
* you previously had no terms of service and are intrudocing them to an app that already has registered users
* for some reason, you have not recorded the agreement of some registered users 

The route named `cp_terms_agree` (default url: `/tos/agree`) points to a page where the user can agree to your latest terms.

The page is only available if you have configured a class with the [`tos` behavior](Resources/docs/behavior.md) in order to record the agreements to the terms. In addition, the `cp_terms.entity_finder` configuration setting must be enabled. Otherwise, the route will trigger a 404 error.

Displaying the differences with earlier terms
---------------------------------------------

In cases where you are seeking the renewed agreement, to your revised terms of service, of a user who has already agreed to a previous version of your terms, it is customary (and understandably justified) to offer your user the possibility to examine the changes in the revised terms, in other words the differences between the previous version that he has already agreed to, and the current, revised version.

The route named `cp_terms_diff` (default url: `/tos/diff`) provides such a page. The page will always display the differences between:

* the current terms of service (i.e. the latest finalized terms of service)
* the terms of service that the user has previously agreed to

The page is only available if you have configured a class with the [`tos` behavior](Resources/docs/behavior.md) in order to record the agreements to the terms. In addition, the `cp_terms.entity_finder` configuration setting must be enabled. Otherwise, the route will trigger a 404 error.

