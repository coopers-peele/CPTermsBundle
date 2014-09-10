<?php

namespace CP\Bundle\TermsBundle\Propel;

use DateTime;

use CP\Bundle\TermsBundle\Propel\om\BaseTerms;

class Terms extends BaseTerms
{
    public static $latest;

    public function getDefaultDiffTarget()
    {
        if ($this->isFinal() && $this->isLatest()) {

            $latest_terms = TermsQuery::create()
                ->latest()
                ->find();

            $terms = $latest_terms->getNext();
        } else {
            $terms = Terms::getLatest();
        }

        return $terms;
    }

    public static function getLatest()
    {
        if (!self::$latest) {
            self::$latest = TermsQuery::create()
                ->latest()
                ->findOne();
        }

        return self::$latest;
    }

    public function __toString()
    {
        return $this->getVersion();
    }

    public function getClone()
    {
        $clone = new Terms();

        $clone->setVersion(sprintf(
            'Cloned from %s on %s',
            $this->getVersion(),
            date('Y-m-d H:i:s')
        ));

        $clone->setClonedFrom($this);

        $clone->setClonedAt(new DateTime());

        $section = SectionQuery::create()
            ->findRoot($this->getId());

        $section->getClone($clone);

        return $clone;
    }

    public function getRoot()
    {
        return SectionQuery::create()
            ->findRoot($this->getId());
    }

    public function getTitle()
    {
        return $this->getRoot()
            ->getTitle();
    }

    public function isFinal()
    {
        return $this->getFinalizedAt() != null;
    }

    public function finalize()
    {
        $this->setFinalizedAt(time());
    }

    public function isLatest()
    {
        return $this->getId() == self::getLatest()->getId();
    }
}
