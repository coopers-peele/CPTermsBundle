<?php

namespace CP\Bundle\TermsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cp_terms');

        $rootNode
            ->children()
                ->scalarNode('date_format')
                    ->defaultValue('dd MMM yy hh:mm')
                ->end()
                ->arrayNode('markdown')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('service')
                            ->defaultValue('cp_terms.markdown.parser.standard')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('diff')
                    ->canBeDisabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('theme')
                            ->defaultValue('bundles/cpterms/css/diff.css')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('entity_finder')
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('service')
                            ->defaultValue('cp_terms.entity_finder.fos_user.propel')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('agreement')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('show_diff')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
