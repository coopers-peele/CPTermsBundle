CPTermsBundle - Configuration
=============================

Reference 
---------

### date_format:

Defines the format for displaying dates.

Default value: `dd MMM yy hh:mm`

### markdown

Specifies the markdown parser to use.

Default value: `cp_terms.markdown.parser.standard`

The CPTermsBundle also provides a Github-flavoured mardown parser (`cp_terms.markdown.parser.github`). You can also implement your own.

### diff

#### theme

A stylesheet used to style the `cp_terms_diff` page.

The value is the path to the stylesheet relative to the app's `web` directory.

Default value: `bundles/cpterms/css/diff.css`

### entity_finder

Defines the service that allows the CPTermsBundle to identify the entity which stores the agreement to your terms (i.e. the entity on which the `tos` behavior has been set).

#### enabled

Default value: false

#### service

The name of the service to use.

Default value: `cp_terms.entity_finder.user`

### agreement

#### show_diff

Specifies whether the `cp_terms_agree` page (see [frontend](frontend.md)) should include a link to the `cp_terms_diff` page. In plain english, this means that if enabled, the page where the user is askled to agree to your terms will include (if appropriate) a link to the page where he can see the differences between the latest terms and the terms that he agree to previously.
