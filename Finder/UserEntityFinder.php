<?php

namespace CP\Bundle\TermsBundle\Finder;

use Symfony\Component\Security\Core\SecurityContextInterface;

class UserEntityFinder implements EntityFinderInterface
{
    protected $context;

    public function __construct(SecurityContextInterface $context)
    {
        $this->context = $context;
    }

    public function findEntity()
    {
        return $this->context->getToken()->getUser();
    }
}
