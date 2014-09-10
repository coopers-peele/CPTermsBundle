
/**
 * Selects users who have agreed the latest terms of service.
 *
 * @return <?php echo $queryClass ?> The current query, for fluid interface
 */
public function haveAgreedToLatestTerms()
{
    $latest  = Terms::getLatest();

    return $this
        ->useAgreementQuery()
            ->filterByTerms($latest)
        ->endUse();
}
