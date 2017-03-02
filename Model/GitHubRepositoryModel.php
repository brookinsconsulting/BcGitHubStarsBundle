<?php
/**
 * File containing the GitHubRepositoryModel class part of the BcGitHubStarsBundle package.
 *
 * @copyright Copyright (C) Brookins Consulting. All rights reserved.
 * @license For full copyright and license information view LICENSE and COPYRIGHT.md file distributed with this source code.
 * @version //autogentag//
 */

namespace BrookinsConsulting\BcGitHubStarsBundle\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use BrookinsConsulting\BcGitHubStarsBundle\Entity\GitHubRepository;

class GitHubRepositoryModel
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var bool
     */
    protected $displayDebug;

    /**
     * @var int
     */
    protected $displayDebugLevel;

    /**
     * Constructor
     *
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $options
     */
    public function __construct(ContainerInterface $container, array $options = array())
    {
        $this->container = $container;
        $this->options = $options[0]['options'];
        $this->displayDebug = $this->options['display_debug'] == true ? true : false;
        $this->displayDebugLevel = is_numeric(
            $this->options['display_debug_level']
        ) ? $this->options['display_debug_level'] : 0;
    }

    /**
     * Create GitHub repositories Entity and store it
     *
     * @param array $repositoryItem
     * @param Symfony\Component\Console\Output\ConsoleOutput $output
     * @param bool $update
     * @return bool
     */
    public function create($repositoryItem, ConsoleOutput $output = null, $update = false)
    {
        $result = false;
        $full_name = $repositoryItem['full_name'];
        $id = $repositoryItem['id'];

        $em = $this->container->get('doctrine')->getManager();
        $existingRepository = $this->getRepositoryByID($id);

        if ($existingRepository === null) {
            $repository = new GitHubRepository();
            $repository->setRepositoryID($id);
            $repository->setFullName($full_name);
            $repository->setName($repositoryItem['name']);
            $repository->setDescription($repositoryItem['description']);
            $repository->setUrl($repositoryItem['url']);
            $repository->setCreatedDate(strtotime($repositoryItem['created_at']));
            $repository->setLastPushDate(strtotime($repositoryItem['pushed_at']));
            $repository->setStargazersCount($repositoryItem['stargazers_count']);

            if ($output->isVerbose()) {
                $output->writeln("Storing repository: ".$full_name);
                if ($output->isVeryVerbose()) {
                    $output->writeln("With repositoryID: ".$id);
                }
            }

            $em->persist($repository);
            $em->flush();
            $result = true;
        } else {
            if ($update == true && $output->isDebug()) {
                $output->writeln("Existing repository found. Updating.\n");
            }
            if ($update === true) {

                $existingRepository->setFullName($full_name);
                $existingRepository->setName($repositoryItem['name']);
                $existingRepository->setDescription($repositoryItem['description']);
                $existingRepository->setUrl($repositoryItem['url']);
                $existingRepository->setCreatedDate(strtotime($repositoryItem['created_at']));
                $existingRepository->setLastPushDate(strtotime($repositoryItem['pushed_at']));
                $existingRepository->setStargazersCount($repositoryItem['stargazers_count']);

                if ($output->isVerbose()) {
                    $output->writeln("Updating repository: ".$full_name);
                    if ($output->isDebug()) {
                        $output->writeln("With repositoryID: ".$id);
                    }
                }

                $em->merge($existingRepository);
                $em->flush();
                $result = true;
            } else {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Create several GitHub repositories Entities
     *
     * @param array $repositoryItems
     * @param Symfony\Component\Console\Output\ConsoleOutput $output
     * @param bool $update
     * @return bool
     */
    public function createRepositories($repositoryItems, ConsoleOutput $output = null, $update = false)
    {
        $result = false;
        $repositoryItemsCount = count($repositoryItems);
        $i = 0;
        while ($i < $repositoryItemsCount) {
            $repositoryItem = $repositoryItems[$i];
            $result = $this->create(
                $repositoryItem, $output, $update
            );

            if ($result) {
                if ($output->isDebug()) {
                    $output->writeln("#".$i." - Repository stored\n");
                }
                $result = true;
            } else {
                $result = false;
            }
            $i++;
        }

        return $result;
    }

    /**
     * Fetch a single GitHub repositories Entity
     *
     * @param int $id
     * @return bool
     */
    public function getRepositoryByID($id)
    {
        $em = $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository("BcGitHubStarsBundle:GitHubRepository")->findOneBy(
            array('repositoryID' => $id));

        return $repository;
    }

    /**
     * Fetch a paginated list of GitHub repositories Entities
     *
     * @param int $limit
     * @return bool
     */
    public function getRepositories($limit = 10, $offset = 0)
    {
        $em = $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository("BcGitHubStarsBundle:GitHubRepository")->findBy(
            array(), array('stargazersCount' => 'DESC'), $limit, $offset);

        return $repository;
    }
}