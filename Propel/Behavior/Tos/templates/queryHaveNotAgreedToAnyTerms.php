
/**
 * Selects users who have not agreed any terms of service.
 *
 * @return <?php echo $queryClass ?> The current query, for fluid interface
 */
public function haveNotAgreedToAnyTerms()
{
    return $this
        ->useAgreementQuery(null, Criteria::LEFT_JOIN)
            ->filterByAgreedAt(null)
        ->endUse()
        ->distinct();
}
