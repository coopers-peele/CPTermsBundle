<?php

namespace CP\Bundle\TermsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LatestTerms extends Constraint
{
    public $message = 'Terms must be latest';
}
