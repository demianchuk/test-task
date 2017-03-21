<?php

namespace AppBundle\Interfaces;

/**
 * Interface for for Github statistic model object.
 *
 * @author Sergii Demianchuk <demianchuk.sergii@gmail.com>
 */
interface GitHubStatisticModelInterface
{
    public function getStarsNumber();
    public function getForksNumber();
    public function getSubscribersNumber();
    public function getLastUpdateDate();
    public function getLastReleaseDate();
    public function getOpenPullRequestNumber();
    public function getClosedPullRequestNumber();
    public function getLastPullRequestMergeDate();
}
