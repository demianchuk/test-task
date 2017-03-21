<?php

namespace AppBundle\DependencyInjection\GitHub;

use AppBundle\Interfaces\GitHubStatisticModelInterface;

/**
 * Class that performs operations related to score estimation
 * Max score is 100 points
 * Min score is 0 points
 *
 * @author Sergii Demianchuk <demianchuk.sergii@gmail.com>
 */
class CalculateScoreService
{
    const WEIGTH_STARS_COEFFICIENT = 3;

    /*
     * Point => Value data mapping for stars
     *
     * @var array
     */
    private $starsScoreMap = array(
        0 => 5,
        1 => 10,
        2 => 30,
        3 => 50,
        4 => 100,
        5 => 200,
        6 => 300,
        7 => 500,
        8 => 1000,
        9 => 2000,
        10 => 5000
    );

    /*
     * Point => Value data maping for forks
     *
     * @var array
     */
    private $forksScoreMap = array(
        0 => 2,
        1 => 5,
        2 => 10,
        3 => 20,
        4 => 30,
        5 => 40,
        6 => 100,
        7 => 200,
        8 => 300,
        9 => 500,
        10 => 1000
    );

    /*
     * Point => Value data mappings for subscribers
     *
     * @var array
     */
    private $subscribersScoreMap = array(
        0 => 5,
        1 => 10,
        2 => 30,
        3 => 50,
        4 => 100,
        5 => 200,
        6 => 300,
        7 => 500,
        8 => 1000,
        9 => 2000,
        10 => 5000
    );

    /*
     * Point => Value data for pull requests
     *
     * @var array
     */
    private $pullRequestScoreMap = array(
        0 => 1,
        1 => 5,
        2 => 10,
        3 => 20,
        4 => 30,
        5 => 50,
        6 => 100,
        7 => 200,
        8 => 300,
        9 => 500,
        10 => 1000
    );

    /*
     * Point => Value data for dates/type
     * statistical info e.g. last update time
     *
     * @var array
     */
    private $dateScoreMap = array(
        0 => 200,
        1 => 120,
        2 => 100,
        3 => 80,
        4 => 60,
        5 => 40,
        6 => 30,
        7 => 20,
        8 => 10,
        9 => 5,
        10 => 2
    );

    /**
     * Calculates score according statistical data
     *
     * @param GitHubStatisticModelInterface $statisticData
     *
     * @return int
     */
    public function calculateScore(GitHubStatisticModelInterface $statisticData) : int
    {
        return $this->calculateStarsPoints($statisticData->getStarsNumber()) +
               $this->calculateForksPoints($statisticData->getForksNumber()) +
               $this->calculateSubscribersPoints($statisticData->getSubscribersNumber()) +
               $this->calculatePullRequestPoints($statisticData->getClosedPullRequestNumber()) +
               $this->calculatePullRequestPoints($statisticData->getOpenPullRequestNumber()) +
               $this->calculateActivityPoints($statisticData->getLastUpdateDate()) +
               $this->calculateActivityPoints($statisticData->getLastPullRequestMergeDate()) +
               $this->calculateActivityPoints($statisticData->getLastReleaseDate());
    }

    /**
     * Calculates points according stars number value
     *
     * @param int $starsNumber
     *
     * @return int
     */
    private function calculateStarsPoints(int $starsNumber) : int
    {
        $points = $this->getPointAccordingClosestValue(
            $starsNumber,
            $this->starsScoreMap
        );

        return $points * self::WEIGTH_STARS_COEFFICIENT;
    }

    /**
     * Calculates points according forks number value
     *
     * @param int $forksNumber
     *
     * @return int
     */
    private function calculateForksPoints(int $forksNumber) : int
    {
        return $this->getPointAccordingClosestValue(
            $forksNumber,
            $this->forksScoreMap
        );
    }

    /**
     * Calculates points according subscribers number value
     *
     * @param int $subscribersNumber
     *
     * @return int
     */
    private function calculateSubscribersPoints(int $subscribersNumber) : int
    {
        return $this->getPointAccordingClosestValue(
            $subscribersNumber,
            $this->subscribersScoreMap
        );
    }

    /**
     * Calculates points according
     * open/closed pull request number value
     *
     * @param int $pullRequestNumber
     *
     * @return int
     */
    private function calculatePullRequestPoints(int $pullRequestNumber)
    {
        return $this->getPointAccordingClosestValue(
            $pullRequestNumber,
            $this->pullRequestScoreMap
        );
    }

    /**
     * Calculates points according date of
     * date statistical value
     *
     * @param date $date
     *
     * @return int
     */
    private function calculateActivityPoints($date) : int
    {
        $date = new \DateTime($date);
        $dateDifference = $date->diff(new \DateTime())->days;

        return $this->getPointAccordingClosestValue(
            $dateDifference,
            $this->dateScoreMap
        );
    }

    /**
     * Search the closest value in array among all values
     * and return key of closest value. In that occassions
     * key represent the number of points
     *
     * @param int $search
     * @param array $arr
     *
     * @return int
     */
    private function getPointAccordingClosestValue($search, $arr) : int
    {
        $closest = null;

        foreach ($arr as $item) {
            if ($closest === null || abs($search - $closest) > abs($item - $search)) {
                $closest = $item;
            }
        }

        return array_search($closest, $arr);
    }
}
