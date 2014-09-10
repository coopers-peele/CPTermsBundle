<?php

namespace CP\Bundle\TermsBundle\Propel;

use CP\Bundle\TermsBundle\Propel\om\BaseSection;

use NestedSetRecursiveIterator;

class Section extends BaseSection
{
    /**
     * Returns a pre-order iterator for this node and its children.
     *
     * @return RecursiveIterator
     */
    public function getIterator()
    {
        return new NestedSetRecursiveIterator($this);
    }

    public function getClone(Terms $terms, Section $parent = null)
    {
        $clone = new Section();

        $clone->setTerms($terms);
        $clone->setTitle($this->getTitle());
        $clone->setContent($this->getContent());

        if ($this->isRoot()) {
            $clone->makeRoot();
        } else {
            $clone->insertAsLastChildOf($parent);
        }

        $clone->save();

        if (!$this->isLeaf()) {
            $children = $this->getChildren();

            foreach ($children as $child) {
                $child->getClone($terms, $clone);
            }
        }

        return $clone;
    }
}
