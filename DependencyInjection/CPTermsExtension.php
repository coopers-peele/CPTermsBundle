<?php

namespace CP\Bundle\TermsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CPTermsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $bundles = $container->getParameter('kernel.bundles');

        $container->setParameter('cp_terms.date_format', $config['date_format']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));

        $loader->load('form_type.yml');

        // markdown
        $loader->load('markdown.yml');
        $container->setAlias('cp_terms.markdown.parser', $config['markdown']['service']);

        // entity finder
        if ($config['entity_finder']['enabled'] && $bundles['FOSUserBundle']) {
            $container->setAlias('cp_terms.entity_finder', $config['entity_finder']['service']);
            $loader->load('entity_finder.yml');
        } else {
            $config['entity_finder']['enabled'] = false;
        }

        // diff
        $loader->load('diff.yml');

        $container->setParameter('cp_terms.agreement.show_diff', $config['agreement']['show_diff']);
        $container->setParameter('cp_terms.diff.theme', $config['diff']['theme']);
    }
}
