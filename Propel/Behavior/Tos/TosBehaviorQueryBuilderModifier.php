<?php

namespace CP\Bundle\TermsBundle\Propel\Behavior\Tos;

class TosBehaviorQueryBuilderModifier
{
    protected $behavior, $builder, $table;

    public function __construct($behavior)
    {
        $this->behavior = $behavior;
        $this->table = $behavior->getTable();
    }

    protected function setBuilder($builder)
    {
        $this->builder = $builder;
        $this->builder->declareClasses('CP\Bundle\TermsBundle\Propel\AgreementPeer');
        $this->builder->declareClasses('CP\Bundle\TermsBundle\Propel\AgreementQuery');
        $this->builder->declareClasses('CP\Bundle\TermsBundle\Propel\Terms');
        $this->builder->declareClasses('CP\Bundle\TermsBundle\Propel\TermsQuery');
    }

    public function queryMethods($builder)
    {
        $this->setBuilder($builder);
        $script = '';

        $script .= $this->addHaveAgreedToLatestTerms();
        $script .= $this->addHaveNotAgreedToAnyTerms();
        $script .= $this->addHaveNotAgreedToLatestTerms();

        return $script;
    }

    protected function addHaveAgreedToLatestTerms()
    {
        return $this->behavior->renderTemplate('queryHaveAgreedToLatestTerms', array(
            'queryClass' => $this->builder->getStubQueryBuilder()->getClassname()
        ));
    }

    protected function addHaveNotAgreedToAnyTerms()
    {
        return $this->behavior->renderTemplate('queryHaveNotAgreedToAnyTerms', array(
            'queryClass' => $this->builder->getStubQueryBuilder()->getClassname()
        ));
    }

    protected function addHaveNotAgreedToLatestTerms()
    {
        return $this->behavior->renderTemplate('queryHaveNotAgreedToLatestTerms', array(
            'queryClass' => $this->builder->getStubQueryBuilder()->getClassname()
        ));
    }

}
