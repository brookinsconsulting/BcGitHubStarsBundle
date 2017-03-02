<?php
/**
 * File containing the GitHubStars class part of the BcGitHubStarsBundle package.
 *
 * @copyright Copyright (C) Brookins Consulting. All rights reserved.
 * @license For full copyright and license information view LICENSE and COPYRIGHT.md file distributed with this source code.
 * @version //autogentag//
 */

namespace BrookinsConsulting\BcGitHubStarsBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Github\Client;
use Github\ResultPager;

class GitHubStars
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
     * @var int
     */
    public $sleepSeconds = 60;

    /**
     * @var string
     */
    public $token;

    /**
     * @var array
     */
    protected $results = null;

    /**
     * @var int
     */
    protected $resultsCount;

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
     * Search for repositories with required stars
     *
     * @param array $values Values to be inserted into pdf template
     * @param Symfony\Component\Console\Output\ConsoleOutput $output
     * @return array $values Returns array of data and return value true if document is created successfully
     */
    public function search(
        $language = 'PHP',
        $starsQuery = '>=100',
        $searchSortByCriteria = 'stars',
        $searchSortOrder = 'desc',
        $store = false,
        $update = false,
        ConsoleOutput $output = null
    ) {
        $result = false;
        // Connect to service
        $client = new Client();
        $client->authenticate($this->token, false, Client::AUTH_HTTP_TOKEN);
        $paginator = new ResultPager($client);
        $searchApi = $client->api('search');

        // Search parameters
        if ($language != false) {
            $searchQuery = array("language:$language", "stars:$starsQuery");
        } else {
            $searchQuery = array("stars:$starsQuery");
        }

        if ($output->isVerbose()) {
            $output->writeln("Sending Search Request ...");
            if ($output->isDebug()) {
                $output->writeln("Query Details ...");
                $output->writeln(var_dump($searchQuery));
                $output->writeln($searchSortByCriteria . " - " . $searchSortOrder);
            }
        }
        // Perform Search
        $results = $paginator->fetchAll(
            $searchApi,
            'repositories',
            $searchQuery,
            $searchSortByCriteria,
            $searchSortOrder
        );
        // Calculate Results
        $resultsInitialCount = count($results);
        $resultsCount = $resultsInitialCount;
        $resultsHasNext = $paginator->hasNext();

        if ($output->isVerbose()) {
            $output->writeln("Received $resultsInitialCount Items");
            if($resultsHasNext == true)
            {
                $output->writeln("Search has additional paginated items");
            }
        }

        $i = 0;
        while ($i < $resultsCount) {
            if ($resultsHasNext == true)
            {
                // Sleeping here avoids github search rate limit of 30 requests per minute
                sleep($this->sleepSeconds);

                // Fetch next paginated results
                $paginatedFetchNextResults = $paginator->fetchNext();

                $paginatedResults = $paginator->fetchAll(
                    $searchApi,
                    'repositories',
                    $searchQuery,
                    $searchSortByCriteria,
                    $searchSortOrder
                );

                // Merge next paginated results into results
                $results = array_merge($results, $paginatedResults);
                $resultsCount = count($paginatedResults);
                $resultsHasNext = $paginator->hasNext();

                if ($output->isVeryVerbose()) {
                    $output->writeln("Received another ".$resultsCount." items");
                }
            }

            // Store results into database
            if ($store == true) {
                if (is_array($results) && count($results) > 0) {
                    $result = $this->container->get(
                        'brookinsconsulting.github_stars.github_repository_model'
                    )->createRepositories($results, $output, $update);
                    // Free memory to prevent exhaustion
                    unset($results);
                    $results = array();
                    $resultsCount = 0;
                }
            }
            $i++;
        }

        if ($resultsCount > 0) {
            $result = array($results, true);
        } elseif ($resultsCount <= 0 && $result) {
            $result = array(array(), true);
        } else {
            $result = false;
        }


        return $result;
    }
}