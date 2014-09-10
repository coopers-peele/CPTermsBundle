<?php

namespace CP\Bundle\TermsBundle\Validator\Constraints;

use CP\Bundle\TermsBundle\Propel\Terms;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LatestTermsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $terms = Terms::getLatest();

        if ($terms->getId() !== $value) {
            $this->context->addViolation(
                $constraint->message
            );
        }
    }
}
