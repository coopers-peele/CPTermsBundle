<?php

namespace CP\Bundle\TermsBundle\Propel\Behavior\Tos;

use CP\Bundle\TermsBundle\Propel\Behavior;

class TosBehaviorObjectBuilderModifier
{
    protected $behavior, $builder, $table;

    public function __construct(Behavior $behavior)
    {
        $this->behavior = $behavior;
        $this->table = $behavior->getTable();
    }

    protected function setBuilder($builder)
    {
        $this->builder = $builder;
        $this->builder->declareClasses('CP\Bundle\TermsBundle\Propel\Terms');
        $this->builder->declareClasses('CP\Bundle\TermsBundle\Propel\TermsQuery');
    }

    public function objectAttributes($builder)
    {
        return $this->behavior->renderTemplate('objectAttributes');
    }

    public function objectMethods($builder)
    {
        $this->setBuilder($builder);
        $script = '';

        $script .= $this->addGetAgreedTerms();
        $script .= $this->addGetLastAgreedTerms();
        $script .= $this->addGetAgreementForLatestTerms();
        $script .= $this->addHasAgreedToLatestTerms();

        return $script;
    }

    protected function addGetAgreedTerms()
    {
        return $this->behavior->renderTemplate('objectGetAgreedTerms');
    }

    protected function addGetLastAgreedTerms()
    {
        return $this->behavior->renderTemplate('objectGetLastAgreedTerms');
    }

    protected function addGetAgreementForLatestTerms()
    {
        return $this->behavior->renderTemplate('objectGetAgreementForLatestTerms');
    }

    protected function addHasAgreedToLatestTerms()
    {
        return $this->behavior->renderTemplate('objectHasAgreedToLatestTerms');
    }
}
