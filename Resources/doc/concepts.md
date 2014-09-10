Basic concepts
=============

Terms of service
------------

The CPTermsBundle manages a collection of `Terms`. An instance of `Terms` represents the terms of service (also called "terms of use", or "conditions of use") for your application. Typically, the users of your application will be required to agree to these terms at some point before being allowed to use the application or some of its features.

Versioning
----------

Terms can be versioned. The version is a text identifying the sequence of modifications made to the terms over time.

The version value can be any text.

Section
-------

A `Section` is a building block of your Terms. Each terms contain a nested set (or tree) of Sections.

A section is defined as:

* a title
* a text content

Lifecycle
---------
The lifecycle of a Terms is as follows:

### Creation

A Terms can be created at any time, either from scratch or by cloning an existing Terms. A Terms created by cloning another Terms "remembers" which terms it was cloned from.

### Finalization

When terms are **finalized**, 2 things happen:

1. The terms are now read-only; no further modifications are allowed on them.
2. The terms become the latest "official" version, i.e. these are the terms that are displayed on your website.
3. When they next access your web site, users will be notified of the changes to the terms and will be asked to agree to the latest changes.

Note: When you display terms on your website, the latest finalized version of the terms is always used. Any previously finalized versions of the terms are ignored. Non-finalized version of your terms will never be displayed on  your website.

### User agreement

When a user signs up to your web site, he will be asked to agree to your terms (the latest version, i.e the last **finalized** instance). Similarly, when a user logs in to your web site and is found not to have agreed to your terms, he will be asked to do so.

Whenever a new version of your terms are finalized, all users, upon their next log in, will be notified of the changes in the terms and will be asked to agree to them.