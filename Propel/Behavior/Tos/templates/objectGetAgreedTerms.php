
/**
 * Returns the terms of service approved by the entity.
 *
 * @return PropelCollection|CP\Bundle\TermsBundle\Propel\Terms the terms approved by entity
 */
public function getAgreedTerms()
{
    return TermsQuery::create()
        ->useAgreementQuery()
            ->filterByEntity($this)
        ->endUse()
        ->finalized()
        ->orderByFinalizedAt('DESC')
        ->withColumn('cp_terms_agreement.agreed_at', 'agreedAt')
        ->find();
}
