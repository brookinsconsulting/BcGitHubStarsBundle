<?php
/**
 * File containing the GitHubStarsController class part of the BcGitHubStarsBundle package.
 *
 * @copyright Copyright (C) Brookins Consulting. All rights reserved.
 * @license For full copyright and license information view LICENSE and COPYRIGHT.md file distributed with this source code.
 * @version //autogentag//
 */

namespace BrookinsConsulting\BcGitHubStarsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class GitHubStarsController extends Controller
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $options

    public function __construct(ContainerInterface $container, array $options = array())
    {
        $this->container = $container;
    }

    public function __construct(RequestStack $requestStack, ContainerInterface $container, array $options = array())
    {
        $this->requestStack = $requestStack;
        $this->container = $container;
    }*/

    public function repositoryListAction(Request $request, $limit = 20, $offset = 0)
    {
        /*
        $repositories = $this->container->get(
            'brookinsconsulting.github_stars.github_repository_model'
        )->getRepositories($limit, $offset);
        */

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT r FROM BcGitHubStarsBundle:GitHubRepository r ORDER BY r.stargazersCount DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $limit/*limit per page*/
        );
        return $this->render('BcGitHubStarsBundle:repository:list.html.twig', array(
            'page_title' => 'GitHubStars: Demo',
            'limit' => $limit,
            'offset' => $offset,
            'pagination' => $pagination
        ));
    }
    public function repositoryListStoreAction(Request $request)
    {
        /*
        var_dump((array) json_decode($request->getContent()));
        die();
        if (is_array($resultsItems) && count($resultsItems) > 0) {
            $result = $this->getContainer()->get(
                'brookinsconsulting.github_stars.github_repository_model'
            )->createRepositories($resultsItems, $output, $updateResults);
        }

        // Return result response
        $response = new Response(json_encode(array('result' => $result)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
        */
    }
    public function repositoryAction($id = 0)
    {
        $repository = $this->container->get(
            'brookinsconsulting.github_stars.github_repository_model'
        )->getRepositoryByID($id);

        return $this->render('BcGitHubStarsBundle:repository:repository.html.twig', array(
            'repository' => $repository
        ));
    }

    public function aboutAction()
    {
        return $this->render('BcGitHubStarsBundle:default:about.html.twig', array(
            'page_title' => 'GitHubStars Demo: About'
        ));
    }
}