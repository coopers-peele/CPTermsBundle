
/**
 * Selects users who have not agreed the latest terms of service, i.e have agreed some earlier terms
 * but not the latest version.
 *
 * @return <?php echo $queryClass ?> The current query, for fluid interface
 */
public function haveNotAgreedToLatestTerms()
{
    $latest  = Terms::getLatest();

    $agreement_entity_id = AgreementQuery::create()
        ->select(AgreementPeer::ENTITY_ID)
        ->filterByTerms($latest)
        ->find();

    return $this
        ->filterById($agreement_entity_id, Criteria::NOT_IN);
}
