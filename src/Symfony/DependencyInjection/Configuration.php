<?php

declare(strict_types=1);

namespace GalacticShrine\GsId\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('gsid');

        $treeBuilder->getRootNode()
            ->children()
                ->enumNode('default_case')
                    ->values(['Upper', 'Lower', 'upper', 'lower', 'Uppercase', 'Lowercase', 'uppercase', 'lowercase'])
                    ->defaultValue('Upper')
                ->end()
                ->enumNode('default_text_format')
                    ->values(['N', 'D', 'n', 'd'])
                    ->defaultValue('D')
                ->end()
                ->enumNode('default_json_format')
                    ->values(['N', 'D', 'n', 'd'])
                    ->defaultValue('D')
                ->end()
                ->enumNode('default_database_format')
                    ->values(['N', 'D', 'n', 'd'])
                    ->defaultValue('N')
                ->end()
                ->booleanNode('lock')
                    ->defaultFalse()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
