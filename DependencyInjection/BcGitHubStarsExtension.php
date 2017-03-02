<?php
/**
 * File containing the BcGitHubStarsExtension class part of the BcGitHubStarsBundle package.
 *
 * @copyright Copyright (C) Brookins Consulting. All rights reserved.
 * @license For full copyright and license information view LICENSE and COPYRIGHT.md file distributed with this source code.
 * @version //autogentag//
 */

namespace BrookinsConsulting\BcGitHubStarsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BcGitHubStarsExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Allow an extension to load the bundle configurations.
     * Here we will load our bundle settings.
     *
     * @param Array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // param $configs (for BC) has always been an empty array so we do this only to silence ezcs warnings for not used constructor variable
        $notUsed = $configs;

        // Load and process Resources/config/githubstars.yml configuration options
        $gitHubStarsConfig = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/githubstars.yml'));

        $gitHubStarsConfiguration = new GitHubStarsConfiguration();
        $processedGitHubStarsConfig = $this->processConfiguration(
            $gitHubStarsConfiguration,
            $gitHubStarsConfig
        );

        $container->setParameter(
            'brookinsconsulting.github_stars.config',
            $processedGitHubStarsConfig
        );

        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yml');
        //$loader->load('routing.yml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     * Here we will load our template selection rules.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        // Loading our YAML file containing our template rules
        // $configFile = __DIR__ . '/../Resources/config/overrides.yml';
        // $config = Yaml::parse( file_get_contents( $configFile ) );

        // We explicitly prepend loaded configuration for "ezpublish" namespace.
        // So it will be placed under the "ezpublish" configuration key, like in ezpublish.yml.
        // $container->prependExtensionConfig( 'ezpublish', $config );
        // $container->addResource( new FileResource( $configFile ) );
        /*
                // Loading our YAML file containing our TWIG Global Variables alias content
                $documentGitHubStarsConfig = Yaml::parse( __DIR__ . '/../Resources/config/githubstars.yml' );

                // Set the settings alias used in the twig global variables settings block
                $container->setParameter(
                    'brookinsconsulting.githubstars.options.config', $documentGitHubStarsConfig['parameters']['options']
                );*/

        // Loading our YAML file containing our TWIG Global Variables
        // $twigConfigFile = __DIR__ . '/../Resources/config/twig_global_parameters.yml';
        // $twigConfig = Yaml::parse( file_get_contents( $twigConfigFile ) );
        // $container->loadFromExtension( 'twig', $twigConfig );
    }

    /**
     * Returns extension alias
     *
     * @return string
     */
    public function getAlias()
    {
        return 'bc_git_hub_stars';
    }
}
