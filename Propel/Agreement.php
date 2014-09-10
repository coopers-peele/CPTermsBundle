<?php

namespace CP\Bundle\TermsBundle\Propel;

use CP\Bundle\TermsBundle\Propel\om\BaseAgreement;

class Agreement extends BaseAgreement
{
    /**
     * Returns whether the agreement is agreed
     *
     * @return boolean
     */
    public function isAgreed()
    {
        return $this->agreed_at ? true : false;
    }
}
