<?php

namespace AppBundle\Interfaces;

/**
 * Interface for for Github client service.
 *
 * @author Sergii Demianchuk <demianchuk.sergii@gmail.com>
 */
interface GitHubServiceInterface
{
    public function getStarsNumber();
    public function getForksNumber();
    public function getSubscribersNumber();
    public function getLastUpdateDate();
    public function getLastReleaseDate();
    public function getOpenPullRequestNumber();
    public function getClosedPullRequestNumber();
    public function getLastPullRequestMergeDate();
    public function getAllGithubStatistics();
}
