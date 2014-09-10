<?php

namespace CP\Bundle\TermsBundle\Twig\Extension;

use cebe\markdown\Parser;

use Twig_Extension;

use Twig_SimpleFilter;

class MarkdownExtension extends Twig_Extension
{
    protected $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('markdown', array($this, 'markdownFilter')),
        );
    }

    public function markdownFilter($text, Parser $parser = null)
    {
        $markup = $this->parser->parse($text);

        return $markup;
    }

    public function getName()
    {
        return 'markdown';
    }
}
