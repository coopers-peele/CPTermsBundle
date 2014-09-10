How to enforce agreement to your terms
======================================

Enforcing agreement means making sure that user of the site has agreed to latest terms of your site. Everytime you publish a new version of your terms of service, If the terms have changed since last agreement between users and site, then again latest terms must be agreed. The following text contains three tutorials for three cases. Choose one appropriate for your case.

Case 1: Check at login only
---------------------------

**Important:** Make sure you have followed the [frontend doc](Resources/doc/frontend.md) including "Applying tos behavior" to User class.

Step 1: Create `LoginSuccessHandler` class:

```php
<?php
// .../Terms/DemoBundle/Security/LoginSuccessHandler.php

namespace CP\Terms\DemoBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LoginSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $token->getUser();

        // check if tos behaviour target has agreed to the terms
        if (!$user->hasAgreedToLatestTerms()) {
            return new RedirectResponse($this->httpUtils->generateUri($request, 'cp_terms_agree'));
        }

        return parent::onAuthenticationSuccess($request, $token);
    }
}

```

Step 2: Create `login_success_handler` service:

```yaml
# .../DemoBundle/Resources/config/login_success.yml
services:
    cp_terms_demo.authentication.login_success_handler:
        class: CP\Terms\DemoBundle\Security\LoginSuccessHandler
        arguments: ["@security.http_utils", {}]
        tags:
            - { name: 'monolog.logger', channel: 'security' }

```

Step 3: Load the service:

```php
<?php

namespace CP\Terms\DemoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
...

class CPTermsDemoExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        ...

        // main added code
        $loader->load('login_success.yml');
    }
}
```

Step 4: Add the listener to `security.yml`:

```yaml
firewalls:
        ...

        main:
            ...
            form_login:
                provider:         fos_userbundle
                csrf_provider:    form.csrf_provider
                # main added code
                success_handler:  cp_terms_demo.authentication.login_success_handler
                ...
```

Now on login, it is checked if user has agreed to latest terms. If not then user is redirected to accept terms page.

Case 2: Special case
--------------------

Users must agree to latest terms to visit any non-public pages. But user with ROLE_ADMIN don't have to accept latest terms to visit any page.

**Important:** Make sure you have followed the [frontend doc](Resources/doc/frontend.md) including "Applying tos behavior" to User class.

Step 1: Create `CheckAgreementListener` class:

```php
<?php
// ...CP/Terms/DemoBundle/EventListener/CheckAgreementListener.php

namespace CP\Terms\DemoBundle\EventListener;

use CP\Bundle\TermsBundle\Exception\TermsNotAgreedException;

use Symfony\Bundle\AsseticBundle\Controller\AsseticController;
use Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\HttpUtils;

class CheckAgreementListener
{
    protected $utils;
    protected $context;

    public function __construct(HttpUtils $utils, SecurityContext $context)
    {
        $this->utils = $utils;
        $this->context = $context;
    }

    public function onFilterController(FilterControllerEvent $event)
    {
        if ($this->context->getToken() &&
                $this->context->isGranted('ROLE_USER') &&
                    // Following ROLES can access page without agreement
                    !$this->context->isGranted('ROLE_ADMIN')) {

            $controller = $event->getController();

            if (!is_array($controller)) {
                return;
            }

            if ($controller[0] instanceof AsseticController ||
                $controller[0] instanceof ProfilerController) {
                return;
            }

            $request = $event->getRequest();

            if ($this->utils->checkRequestPath($request, 'cp_terms_agree') ||
                    $this->utils->checkRequestPath($request, 'cp_terms') ||
                        $request->attributes->get('_controller') == 'CPTermsBundle:Frontend:agree') {
                return;
            }

            $user = $this->context->getToken()->getUser();

            // check if tos behaviour target has agreed to the terms
            if (!$user->hasAgreedToLatestTerms()) {
                throw new TermsNotAgreedException('You must agree to latest terms.');
            }
        }
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!($exception instanceof TermsNotAgreedException)) {
            return;
        }

        $response = $this->utils->createRedirectResponse($event->getRequest(), 'cp_terms_agree');

        $event->setResponse($response);
    }
}
```

Step 2: add service for listener:

```yaml
# CP/Terms/DemoBundle/Resources/config/agreement.yml
services:
    cp_terms_demo.agreement.listener:
        class: 'CP\Terms\DemoBundle\EventListener\CheckAgreementListener'
        arguments: [@security.http_utils, @security.context]
        tags:
            - { name: kernel.event_listener, event: kernel.controller, method: onFilterController }
            - { name: kernel.event_listener, event: kernel.exception, method: onKernelException }

```

Step 3: Load the service:

```php
<?php

namespace CP\Terms\DemoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
...

class CPTermsDemoExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        ...

        // main added code
        $loader->load('agreement.yml');
    }
}
```

Case 3: Simple
--------------

Users must agree to latest terms to visit any non-public pages.

**Important:** Make sure you have followed the [frontend doc](Resources/doc/frontend.md) including "Applying tos behavior" to User class.

1. Same as (Case 2) Just remove the `ROLE_ADMIN` check part from listener

**Note:** Tutorial assumes you are using `FOSUserBundle` and User class has the `tos` behavior.
