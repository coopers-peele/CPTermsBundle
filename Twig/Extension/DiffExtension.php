<?php

namespace CP\Bundle\TermsBundle\Twig\Extension;

use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFilter;

use cogpowered\FineDiff\Diff;
use cogpowered\FineDiff\Granularity\Sentence;

use CP\Bundle\TermsBundle\Propel\Terms;

class DiffExtension extends Twig_Extension
{
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter(
                'diff',
                array($this, 'diff'),
                array(
                    'is_safe' => array('html'),
                    'needs_environment' => true
                )
            ),
        );
    }

    public function diff(Twig_Environment $environment, Terms $terms, Terms $with_terms)
    {
        $view_name = $this->getTermsViewName();

        $terms_content = $environment->render(
            $view_name,
            array(
                'root'=> $terms->getRoot()
            )
        );

        $with_terms_content = $environment->render(
            $view_name,
            array(
                'root'=> $with_terms->getRoot()
            )
        );

        $granularity = new Sentence();

        $diff = new Diff($granularity);

        return html_entity_decode($diff->render($terms_content, $with_terms_content, $granularity));
    }

    public function getName()
    {
        return 'diff';
    }

    protected function getTermsViewName()
    {
        return 'CPTermsBundle:Frontend:tos.html.twig';
    }
}
