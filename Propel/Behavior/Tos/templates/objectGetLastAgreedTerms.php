
/**
 * Returns the last terms of service approved by the entity. These may not be
 * the latest terms of service.
 *
 * @return CP\Bundle\TermsBundle\Propel\Terms the last terms approved by entity if they exist
 *         null if none
 */
public function getLastAgreedTerms()
{
    return TermsQuery::create()
        ->useAgreementQuery()
            ->filterByEntity($this)
        ->endUse()
        ->finalized()
        ->orderByFinalizedAt('DESC')
        ->withColumn('cp_terms_agreement.agreed_at', 'agreedAt')
        ->findOne();
}
