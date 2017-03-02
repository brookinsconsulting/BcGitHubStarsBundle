<?php
/**
 * File containing the Builder class part of the BcGitHubStarsBundle package.
 *
 * @copyright Copyright (C) Brookins Consulting. All rights reserved.
 * @license For full copyright and license information view LICENSE and COPYRIGHT.md file distributed with this source code.
 * @version //autogentag//
 */

namespace BrookinsConsulting\BcGitHubStarsBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function leftNavbarMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('About', array('route' => 'about'));
        $menu->addChild('List', array('route' => 'repositoryList'));
        $menu->addChild('Limit');
        $menu['Limit']->addChild('20', array('route' => 'repositoryListWithParams', 'routeParameters' => array('limit' => 20)));
        $menu['Limit']->addChild('50', array('route' => 'repositoryListWithParams', 'routeParameters' => array('limit' => 50)));
        $menu['Limit']->addChild('100', array('route' => 'repositoryListWithParams', 'routeParameters' => array('limit' => 100)));
        $menu['Limit']->addChild('250', array('route' => 'repositoryListWithParams', 'routeParameters' => array('limit' => 250)));

        return $menu;
    }
}