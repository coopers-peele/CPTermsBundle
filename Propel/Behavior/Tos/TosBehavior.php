<?php

namespace CP\Bundle\TermsBundle\Propel\Behavior\Tos;

use Behavior;

use ForeignKey;

class TosBehavior extends Behavior
{
    protected $objectBuilderModifier;

    protected $queryBuilderModifier;

    public function getObjectBuilderModifier()
    {
        if (is_null($this->objectBuilderModifier)) {

            $this->objectBuilderModifier = new TosBehaviorObjectBuilderModifier($this);
        }

        return $this->objectBuilderModifier;
    }

    public function getQueryBuilderModifier()
    {
        if (is_null($this->queryBuilderModifier)) {

            $this->queryBuilderModifier = new TosBehaviorQueryBuilderModifier($this);
        }

        return $this->queryBuilderModifier;
    }

    public function modifyTable()
    {
        $table = $this->getTable();
        $database = $table->getDatabase();

        $agreementTable = $database->getTable('cp_terms_agreement');

        $fkEntity = new ForeignKey('FI_cp_terms_agreement_entity');
        $fkEntity->setForeignTableCommonName($table->getCommonName());
        $fkEntity->setForeignSchemaName($table->getSchema());
        $fkEntity->addReference('entity_id', $table->getFirstPrimaryKeyColumn()->getName());
        $fkEntity->setPhpName('Entity');

        $agreementTable->addForeignKey($fkEntity);
    }
}
