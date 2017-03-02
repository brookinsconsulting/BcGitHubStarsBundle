<?php
/**
 * File containing the GitHubStarsConfiguration class part of the BcGitHubStarsBundle package.
 *
 * @copyright Copyright (C) Brookins Consulting. All rights reserved.
 * @license For full copyright and license information view LICENSE and COPYRIGHT.md file distributed with this source code.
 * @version //autogentag//
 */

namespace BrookinsConsulting\BcGitHubStarsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class GitHubStarsConfiguration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $TreeBuilder = new TreeBuilder();

        $RootNode = $TreeBuilder->root( 'parameters' );

        $RootNode
            ->children()
                ->arrayNode( 'options' )
                    ->info( 'Available hourly invoice options' )
                    ->isRequired()
                    ->children()
                        ->booleanNode( 'display_debug' )
                            ->defaultFalse()
                            ->isRequired()
                        ->end()
                        ->scalarNode( 'display_debug_level' )
                            ->isRequired()
                        ->end()
                        ->scalarNode( 'default_handler' )
                            ->isRequired()
                        ->end()
                        ->scalarNode( 'default_template' )
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode( 'values' )
                    ->info( 'Available values options' )
                    ->isRequired()
                    ->children()
                        ->scalarNode( 'percentage' )
                            ->defaultValue( '%' )
                            ->isRequired()
                        ->end()
                        ->scalarNode( 'dollar_sign' )
                            ->defaultValue( '$' )
                            ->isRequired()
                        ->end()
                        ->scalarNode( 'random_range_start' )
                            ->defaultValue( '100000000' )
                            ->isRequired()
                        ->end()
                        ->scalarNode( 'random_range_end' )
                            ->defaultValue( '99999999999999999' )
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode( 'handlers' )
                    ->info( 'Available file link helper applications' )
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype( 'array' )
                    ->children()
                        ->scalarNode( 'name' )
                            ->isRequired()
                        ->end()
                        ->arrayNode( 'extension' )
                            ->isRequired()
                            ->prototype( 'scalar' )->end()
                        ->end()
                        ->scalarNode( 'viewer_name' )
                            ->isRequired()
                        ->end()
                        ->scalarNode( 'viewer_url' )
                            ->isRequired()
                        ->end()
                        ->scalarNode( 'class' )
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $TreeBuilder;
    }
}
