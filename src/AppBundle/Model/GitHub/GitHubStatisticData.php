<?php

namespace AppBundle\Model\GitHub;

use AppBundle\Interfaces\GitHubStatisticModelInterface;

class GitHubStatisticData implements GitHubStatisticModelInterface
{
    /*
     * @var integer
     */
    private $starsNumber;

    /*
     * @var integer
     */
    private $forksNumber;

    /*
     * @var integer
     */
    private $subscribesNumber;

    /*
     * @var integer
     */
    private $openPullRequestNumber;

    /*
     * @var integer
     */
    private $closedPullRequestNumber;

    /*
     * @var dateTime
     */
    private $lastUpdateDate;

    /*
     * @var dateTime
     */
    private $lastReleaseDate;

    /*
     * @var dateTime
     */
    private $lastPullRequestMergeDate;

    /**
     * @param int $starsNumber
     */
    public function setStarsNumber($starsNumber)
    {
        $this->starsNumber = $starsNumber;
    }

    /**
     * @return int
     */
    public function getStarsNumber()
    {
        return $this->starsNumber;
    }

    /**
     * @param int $forksNumber
     */
    public function setForksNumber($forksNumber)
    {
        $this->forksNumber = $forksNumber;
    }

    /**
     * @return int
     */
    public function getForksNumber()
    {
        return $this->forksNumber;
    }

    /**
     * @param int $subscribesNumber
     */
    public function setSubscribersNumber($watchersNumber)
    {
        $this->subscribesNumber = $watchersNumber;
    }

    /**
     * @return int
     */
    public function getSubscribersNumber()
    {
        return $this->subscribesNumber;
    }

    /**
     * @param int $openPullRequestNumber
     */
    public function setOpenPullRequestNumber($openPullRequestNumber)
    {
        $this->openPullRequestNumber = $openPullRequestNumber;
    }

    /**
     * @return int
     */
    public function getOpenPullRequestNumber()
    {
        return $this->openPullRequestNumber;
    }

    /**
     * @param int $closedPullRequestNumber
     */
    public function setClosedPullRequestNumber($closedPullRequestNumber)
    {
        $this->closedPullRequestNumber = $closedPullRequestNumber;
    }

    /**
     * @return int
     */
    public function getClosedPullRequestNumber()
    {
        return $this->closedPullRequestNumber;
    }

    /**
     * @param dateTime $lastUpdateDate
     */
    public function setLastUpdateDate($lastUpdateDate)
    {
        $this->lastUpdateDate = $lastUpdateDate;
    }

    /**
     * @return dateTime
     */
    public function getLastUpdateDate()
    {
        return $this->lastUpdateDate;
    }

    /**
     * @param dateTime $lastReleaseDate
     */
    public function setLastReleaseDate($lastReleaseDate)
    {
        $this->lastReleaseDate = $lastReleaseDate;
    }

    /**
     * @return dateTime
     */
    public function getLastReleaseDate()
    {
        return $this->lastReleaseDate;
    }

    /**
     * @param dateTime $lastPullRequestMergeDate
     */
    public function setLastPullRequestMergeDate($lastPullRequestMergeDate)
    {
        $this->lastPullRequestMergeDate = $lastPullRequestMergeDate;
    }

    /**
     * @return dateTime
     */
    public function getLastPullRequestMergeDate()
    {
        return $this->lastPullRequestMergeDate;
    }
}
