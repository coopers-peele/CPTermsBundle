
/*
 * adds latest term to new agreement
 */
public function setAgreementForLatestTerms(Agreement $agreement)
{
    if ($agreement->isNew()) {
        $terms = Terms::getLatest();

        if (!$terms) {
            return null;
        }

        $agreement->setTerms($terms);

        $this->agreement = $agreement;
    }
}
