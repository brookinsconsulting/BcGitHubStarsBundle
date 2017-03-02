<?php

namespace BrookinsConsulting\BcGitHubStarsBundle\Entity;

/**
 * GitHubRepository
 */
class GitHubRepository
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $repositoryID;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $stargazersCount;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var \DateTime
     */
    private $lastPushDate;

    /**
     * @var string
     */
    private $fullName;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set repositoryID
     *
     * @param integer $repositoryID
     *
     * @return GitHubRepository
     */
    public function setRepositoryID($repositoryID)
    {
        $this->repositoryID = $repositoryID;

        return $this;
    }

    /**
     * Get repositoryID
     *
     * @return int
     */
    public function getRepositoryID()
    {
        return $this->repositoryID;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return GitHubRepository
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return GitHubRepository
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return GitHubRepository
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set stargazersCount
     *
     * @param integer $stargazersCount
     *
     * @return GitHubRepository
     */
    public function setStargazersCount($stargazersCount)
    {
        $this->stargazersCount = $stargazersCount;

        return $this;
    }

    /**
     * Get stargazersCount
     *
     * @return int
     */
    public function getStargazersCount()
    {
        return $this->stargazersCount;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return GitHubRepository
     */
    public function setCreatedDate($createdDate)
    {
        $date = new \DateTime();
        $this->createdDate = $date->setTimestamp($createdDate);

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate->getTimestamp();
    }

    /**
     * Set lastPushDate
     *
     * @param \DateTime $lastPushDate
     *
     * @return GitHubRepository
     */
    public function setLastPushDate($lastPushDate)
    {
        $date = new \DateTime();
        $this->lastPushDate = $date->setTimestamp($lastPushDate);

        return $this;
    }

    /**
     * Get lastPushDate
     *
     * @return \DateTime
     */
    public function getLastPushDate()
    {
        return $this->lastPushDate->getTimestamp();
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return GitHubRepository
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }
}
