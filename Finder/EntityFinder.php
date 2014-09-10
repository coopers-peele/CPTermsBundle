<?php

namespace CP\Bundle\TermsBundle\Finder;

use Symfony\Component\Security\Core\SecurityContextInterface;

class EntityFinder implements EntityFinderInterface
{
    protected $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function findEntity()
    {
        return $this->securityContext->getToken()->getUser();
    }
}
