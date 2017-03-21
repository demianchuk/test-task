<?php

namespace Tests\AppBundle\Controller;

use PHPUnit\Framework\TestCase;
use AppBundle\DependencyInjection\GitHub\CalculateScoreService;
use AppBundle\Model\GitHub\GitHubStatisticData;

class CalculateScoreServiceTest extends TestCase
{
    protected function setUp()
    {
        $this->model = new GitHubStatisticData();
        $this->service = new CalculateScoreService();
    }

    public function testCalculateScore()
    {
        //test expected value
        $this->model->setStarsNumber(1022);
        $this->model->setForksNumber(366);
        $this->model->setSubscribersNumber(82);
        $this->model->setClosedPullRequestNumber(30);
        $this->model->setOpenPullRequestNumber(12);
        $this->model->setLastReleaseDate('2016-12-13T13:10:16Z');
        $this->model->setLastUpdateDate('2017-03-18T05:10:36Z');
        $this->model->setLastPullRequestMergeDate('2017-02-23T11:42:20Z');

        $this->assertEquals(60, $this->service->calculateScore($this->model));

        //test zero value
        $this->model->setStarsNumber(0);
        $this->model->setForksNumber(0);
        $this->model->setSubscribersNumber(0);
        $this->model->setClosedPullRequestNumber(0);
        $this->model->setOpenPullRequestNumber(0);
        $this->model->setLastReleaseDate('2011-12-13T13:10:16Z');
        $this->model->setLastUpdateDate('2011-03-18T05:10:36Z');
        $this->model->setLastPullRequestMergeDate('2011-02-23T11:42:20Z');

        $this->assertEquals(0, $this->service->calculateScore($this->model));

        //test max value
        $maxCounter = 10000;
        $currentDate = new \DateTime();

        $this->model->setStarsNumber($maxCounter);
        $this->model->setForksNumber($maxCounter);
        $this->model->setSubscribersNumber($maxCounter);
        $this->model->setClosedPullRequestNumber($maxCounter);
        $this->model->setOpenPullRequestNumber($maxCounter);
        $this->model->setLastReleaseDate($currentDate->format('Y-m-d H:i:s'));
        $this->model->setLastUpdateDate($currentDate->format('Y-m-d H:i:s'));
        $this->model->setLastPullRequestMergeDate($currentDate->format('Y-m-d H:i:s'));

        $this->assertEquals(100, $this->service->calculateScore($this->model));
    }
}
