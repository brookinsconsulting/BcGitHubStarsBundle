<?php
/**
 * File containing the GitHubStarsCommand class part of the BcGitHubStarsBundle package.
 *
 * @copyright Copyright (C) Brookins Consulting. All rights reserved.
 * @license For full copyright and license information view LICENSE and COPYRIGHT.md file distributed with this source code.
 * @version //autogentag//
 */

namespace BrookinsConsulting\BcGitHubStarsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GitHubStarsCommand extends ContainerAwareCommand
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
     * @var array
     */
    protected $questionAnswers = array();

    /**
     * @var array
     */
    protected $values = array();

    /**
     * @var array
     */
    protected $searchResults = array();

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $starsQuery;

    /**
     * @var string
     */
    protected $sortBy;

    /**
     * @var string
     */
    protected $sortOrder;

    /**
     * @var bool
     */
    protected $iterationStore;

    /**
     * @var bool
     */
    protected $update;

    /**
     * Configure hourly invoice document creation command line options
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('bc:gs:import')
            ->setDescription('Import GitHub Stars Data')
            ->addOption(
                'token',
                null,
                InputOption::VALUE_REQUIRED,
                'The string of the GitHub API Token',
                false
            )
            ->addOption(
                'starsQuery',
                null,
                InputOption::VALUE_OPTIONAL,
                'The integer of the minimum number of GitHub Stars',
                ">5000"
            )
            ->addOption(
                'language',
                null,
                InputOption::VALUE_OPTIONAL,
                'The string of the language of the GitHub repository',
                "PHP"
            )
            ->addOption(
                'sortBy',
                null,
                InputOption::VALUE_OPTIONAL,
                'The string of the sort by criteria: updated, starts, etc',
                "'stars'"
            )
            ->addOption(
                'sortOrder',
                null,
                InputOption::VALUE_OPTIONAL,
                'The string of the sort order: asc or desc',
                "'desc'"
            )
            ->addOption(
                'iterationStore',
                null,
                InputOption::VALUE_NONE,
                'The boolean value of when to store records. true or false'
            )
            ->addOption(
                'update',
                null,
                InputOption::VALUE_NONE,
                'The boolean value of if existing records should be updated. true or false'
            );
    }

    /**
     * Execute command
     *
     * @param Symfony\Component\Console\Input\InputInterface $input
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        // Ask document creation requirement questions
        $this->helper = $this->getHelper('question');
        $this->optionsQuestions($input, $output);
        $this->setParameters($input);

        // Search for GitHub Stars Data
        $this->search($output);

        // Store GitHub Stars Data
        $this->store($output);
    }

    /**
     * Ask Options Questions
     *
     * @param Symfony\Component\Console\Input\InputInterface $input
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function optionsQuestions(
        InputInterface $input,
        OutputInterface $output
    ) {
        // Access helper object
        $helper = $this->helper;

        // Get Option Input and Ask Remaining Option Questions

        // Ask: GitHub Token Option Question
        if ($input->getOption('token') === null) {
            $qGitHubToken = new Question(
                "What is the GitHub API Token? (Required): ", false
            );

            $aGitHubToken = $helper->ask($input, $output, $qGitHubToken);

            if ($aGitHubToken) {
                $this->questionAnswers['token'] = $aGitHubToken;
            }
        }

        // Ask: GitHub Minimum Stars Option Question
        if ($input->getOption('starsQuery') === null) {
            $qGitHubMinimumStarsCount = new Question(
                "What is the minimum number of GitHub Stars to consider? (Required, Defaults to 50): ", 50
            );

            $aGitHubMinimumStarsCount = $helper->ask($input, $output, $qGitHubMinimumStarsCount);

            if ($aGitHubMinimumStarsCount) {
                $this->questionAnswers['starsQuery'] = $aGitHubMinimumStarsCount;
            }
        }

        // Ask: GitHub Repository Language Option Question
        if ($input->getOption('language') === null) {
            $qGitHubRepositoryLanguage = new Question(
                "What is the language string of the GitHub repositories to consider? (Required, Defaults to 'PHP'): ",
                "PHP"
            );

            $aGitHubRepositoryLanguage = $helper->ask($input, $output, $qGitHubRepositoryLanguage);

            if ($aGitHubRepositoryLanguage) {
                $this->questionAnswers['language'] = $aGitHubRepositoryLanguage;
            }
        }

        // Ask: GitHub Sort By Criteria Option Question
        if ($input->getOption('sortBy') === null) {
            $qGitHubSortByCriteria = new Question(
                "What is the GitHub sort by criteria to consider? (Required, Defaults to 'stars'): ", "'stars'"
            );

            $aGitHubSortByCriteria = $helper->ask($input, $output, $qGitHubSortByCriteria);

            if ($aGitHubSortByCriteria) {
                $this->questionAnswers['sortBy'] = $aGitHubSortByCriteria;
            }
        }

        // Ask: GitHub Sort Order Option Question
        if ($input->getOption('sortOrder') === null) {
            $qGitHubSortOrder = new Question(
                "What is the GitHub sort order to consider? (Required, Defaults to 'desc'): ", "'desc'"
            );

            $aGitHubSortOrder = $helper->ask($input, $output, $qGitHubSortOrder);

            if ($aGitHubSortOrder) {
                $this->questionAnswers['sortOrder'] = $aGitHubSortOrder;
            }
        }

        // Ask: GitHub update repositories Option Question
        if ($input->getOption('update') === null) {
            $qUpdate = new Question(
                "Should records be updated? (Required, Defaults to 'false'): ", false
            );

            $aUpdate = $helper->ask($input, $output, $qUpdate);

            if ($aUpdate) {
                $this->questionAnswers['update'] = $aUpdate;
            }
        }
    }

    /**
     * Set Commandline Parameters as object properties
     *
     * @param Symfony\Component\Console\Input\InputInterface $input
     * @return false
     */
    public function setParameters(
        InputInterface $input
    ) {
        // Get options
        $this->values = array_merge($input->getOptions(), $this->questionAnswers);

        // Get the github token
        if (array_key_exists('token', $this->values)) {
            $this->token = $this->values['token'];
        } else {
            $this->token = false;
        }

        // Get the minimum stars count
        if (array_key_exists('starsQuery', $this->values)) {
            $this->starsQuery = $this->values['starsQuery'];
        } else {
            $this->starsQuery = 50;
        }

        // Get the repository language
        if (array_key_exists('language', $this->values) && $this->values['language'] != false) {
            $this->language = $this->values['language'];
        } elseif (array_key_exists('language', $this->values) && $this->values['language'] === false) {
            $this->language = false;
        } else {
            $this->language = "PHP";
        }

        // Get the sort by criteria
        if (array_key_exists('sortBy', $this->values)) {
            $this->sortBy = $this->values['sortBy'];
        } else {
            $this->sortBy = 'stars';
        }

        // Get the sort order
        if (array_key_exists('sortOrder', $this->values)) {
            $this->sortOrder = $this->values['sortOrder'];
        } else {
            $this->sortOrder = 'desc';
        }

        // Get the iteration storage defaults
        if (array_key_exists('iterationStore', $this->values)) {
            $this->iterationStore = $this->values['iterationStore'];
        } else {
            $this->iterationStore = true;
        }

        // Get the update storage defaults
        if (array_key_exists('update', $this->values)) {
            $this->update = $this->values['update'];
        } else {
            $this->update = false;
        }

        return false;
    }

    /**
     * Search for GitHub repositories with stars
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @return bool
     */
    public function search(
        OutputInterface $output
    ) {
        // Search for content to import
        if ($this->token) {
            // Get GitHubStars service
            $gitHubStars = $this->getContainer()->get('brookinsconsulting.github_stars.github_stars');
            // Assign GitHub Api Token
            $gitHubStars->token = $this->token;
            // Search for most stared projects
            $result = $gitHubStars->search(
                $this->language,
                $this->starsQuery,
                $this->sortBy,
                $this->sortOrder,
                $this->iterationStore,
                $this->update,
                $output
            );
        } else {
            $result = false;
        }

        // Display results to cli buffer
        if (is_array($result) && $result[1] === true) {
            // Assign results to object property for later storage into database
            $this->searchResults = $result[0];
            $output->writeln("Success! GitHub repository data acquired");
            $result = true;
        } else {
            $output->writeln("GitHub repository search failed to return unique results");
        }

        return $result;
    }

    /**
     * Store GitHub repositories with stars data
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @return bool
     */
    public function store(
        OutputInterface $output
    ) {
        $result = false;
        $resultsItems = $this->searchResults;
        $updateResults = $this->update;

        // Only store records if they were not already stored
        if (!$this->iterationStore) {
            // Store results into database
            if (is_array($resultsItems) && count($resultsItems) > 0) {
                $result = $this->getContainer()->get(
                    'brookinsconsulting.github_stars.github_repository_model'
                )->createRepositories($resultsItems, $output, $updateResults);
            }

            // Display results to cli buffer
            if ($result === true) {
                $output->writeln("Success! GitHub repository data stored\n");
            } else {
                $output->writeln("GitHub repository data was not stored\n");
            }
        }

        return $result;
    }
}