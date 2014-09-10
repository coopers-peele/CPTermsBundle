
/**
 * Indicates whether the entity has approved the latest (finalized) terms of service.
 *
 * @return boolean true if the entity has approved the latest (finalized) terms of service,
 *                 false otherwise
 */
public function hasAgreedToLatestTerms()
{
    $terms = Terms::getLatest();

    if (!$terms) {
        return false;
    }

    $count = AgreementQuery::create()
        ->filterByEntity($this)
        ->filterByTerms($terms)
        ->count();

    return $count ? true : false;
}
