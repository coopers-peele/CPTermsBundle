<?php

namespace CP\Bundle\TermsBundle\Propel;

use CP\Bundle\TermsBundle\Propel\om\BaseTermsQuery;

use Criteria;

class TermsQuery extends BaseTermsQuery
{
    public function latest()
    {
        return $this
            ->finalized()
            ->orderByFinalizedAt(Criteria::DESC);
    }

    public function finalized()
    {
        return $this
            ->filterByFinalizedAt(null, Criteria::ISNOTNULL);
    }

    public function editable()
    {
        return $this
            ->filterByFinalizedAt(null);
    }
}
