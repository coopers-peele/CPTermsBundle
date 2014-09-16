<?php

namespace CP\Bundle\TermsBundle\Validator\Constraints;

use CP\Bundle\TermsBundle\Propel\Terms;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LatestTermsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $terms = Terms::getLatest();

        if ($terms->getId() !== $value) {
            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            } else {
                // 2.4 API
                $this->context->addViolation(
                    $constraint->message
                );
            }
        }
    }
}
