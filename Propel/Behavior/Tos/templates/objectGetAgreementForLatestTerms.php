
/**
 * Returns an Agreement instance for the latest terms.
 *
 * @return CP\Bundle\TermsBundle\Propel\Agreement agreement instance for latest terms.
 *         new CP\Bundle\TermsBundle\Propel\Agreement if not available
 */
public function getAgreementForLatestTerms()
{
    if (!$this->agreement) {
        $terms = Terms::getLatest();

        if (!$terms) {
            return null;
        }

        $agreement = AgreementQuery::create()
            ->filterByEntity($this)
            ->filterByTerms($terms)
            ->findOne();

        if (!$agreement) {
            $agreement = new Agreement();
            $agreement->setEntity($this);
            $agreement->setTerms($terms);
        }

        $this->agreement = $agreement;
    }

    return $this->agreement;
}
