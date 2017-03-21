<?php

namespace AppBundle\DependencyInjection\GitHub;

use AppBundle\Interfaces\GitHubServiceInterface;
use Github\Client as GithubClient;
use Psr\Log\LoggerInterface;
use AppBundle\Model\GitHub\GitHubStatisticData;
use AppBundle\Interfaces\GitHubStatisticModelInterface;

/**
 * Class that performs operations related to getting
 * info for github repositories.
 *
 * @author Sergii Demianchuk <demianchuk.sergii@gmail.com>
 */
class GitHubService implements GitHubServiceInterface
{
    const PULL_REQUEST_CLOSED = 'closed';
    const PULL_REQUEST_OPEN = 'open';
    const PULL_REQUEST_ALL = 'all';

    /**
     * @var \Github\Client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $repoName;


    /**
     * @param GithubClient $client
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function __construct(GithubClient $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setSourceUrl(string $url)
    {
        // Split the URL into its constituent parts.
        $parse = parse_url($url);

        // Remove the leading forward slash, if there is one.
        $path = ltrim($parse['path'], '/');

        // Put each element into an array.
        $elements = explode('/', $path);

        $this->userName = $elements[0];
        $this->repoName = $elements[1];

        return $this;
    }

    /**
     * @param string $userName
     * @param string $repoName
     *
     * @return $this
     */
    public function setSourceAsNames(string $userName, string $repoName)
    {
        $this->userName = $userName;
        $this->repoName = $repoName;

        return $this;
    }

    /**
     * @return \Github\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return int|null
     */
    public function getStarsNumber()
    {
        $data = $this->getStatisticsData();

        return $data['stargazers_count'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getForksNumber()
    {
        $data = $this->getStatisticsData();

        return $data['forks_count'] ?? null ;
    }

    /**
     * @return int|null
     */
    public function getSubscribersNumber()
    {
        $data = $this->getStatisticsData();

        return $data['subscribers_count'] ?? null;
    }

    /**
     * @return date|null
     */
    public function getLastUpdateDate()
    {
        $data = $this->getStatisticsData();

        return $data['updated_at'] ?? null;
    }

    /**
     * @return date|null
     */
    public function getLastPullRequestMergeDate()
    {
        $data = $this->getPullRequestData(self::PULL_REQUEST_ALL);

        foreach ($data as $pullRequest) {
            if (isset($pullRequest['merged_at']) && !empty($pullRequest['merged_at'])) {
                return $pullRequest['merged_at'];
            }
        }

        return null;
    }

    /**
     * @return date|null
     */
    public function getLastReleaseDate()
    {
        $data = $this->getReleasesData();

        return $data['published_at'] ?? null;
    }

    /**
     * @return int
     */
    public function getOpenPullRequestNumber() : int
    {
        $data = $this->getPullRequestData(self::PULL_REQUEST_OPEN);

        return count($data);
    }

    /**
     * @return int
     */
    public function getClosedPullRequestNumber() : int
    {
        $data = $this->getPullRequestData(self::PULL_REQUEST_CLOSED);

        return count($data);
    }

    /**
     * @return interface GitHubStatisticModelInterface
     */
    public function getAllGithubStatistics() : GitHubStatisticModelInterface
    {
        $gitHubStatistic = new GitHubStatisticData();

        $statisticData = $this->getStatisticsData();

        //general statistic info
        $gitHubStatistic->setStarsNumber($statisticData['stargazers_count'] ?? null);
        $gitHubStatistic->setForksNumber($statisticData['forks_count'] ?? null);
        $gitHubStatistic->setSubscribersNumber($statisticData['subscribers_count'] ?? null);
        $gitHubStatistic->setLastUpdateDate($statisticData['updated_at'] ?? null);
        //pull requests info
        $gitHubStatistic->setOpenPullRequestNumber($this->getOpenPullRequestNumber());
        $gitHubStatistic->setClosedPullRequestNumber($this->getClosedPullRequestNumber());
        $gitHubStatistic->setLastPullRequestMergeDate($this->getLastPullRequestMergeDate());
        //release info
        $gitHubStatistic->setLastReleaseDate($this->getLastReleaseDate());

        return $gitHubStatistic;
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    private function getStatisticsData()
    {
        $data = array();

        if (!$this->userName || !$this->repoName) {
            throw new \Exception(
                'Username and reponame provided via setSourceUrl method are not correct'
            );
        }

        try {
            $data = $this->client->api('repo')->show(
                $this->userName,
                $this->repoName
            );
        } catch (\Exception $e) {
            $this->logger->error(
                'UserName: '.$this->userName.'. RepoName: '.$this->repoName.
                '. Message: '.$e->getMessage()
            );
        }

        return $data;
    }

    /**
     * @param string open|closed|all
     * @return array
     *
     * @throws \Exception
     */
    private function getPullRequestData($type)
    {
        $data = array();

        if (!$this->userName || !$this->repoName) {
            throw new \Exception(
                'Username and reponame provided via setSourceUrl method are not correct'
            );
        }

        try {
            $data = $this->client->api('pull_request')->all(
                $this->userName,
                $this->repoName,
                ['state' => $type]
            );
        } catch (\Exception $e) {
            $this->logger->error(
                'UserName: '.$this->userName.'. RepoName: '.$this->repoName.
                ' Type: '.$type.
                '. Message: '.$e->getMessage()
            );
        }

        return $data;
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    private function getReleasesData()
    {
        $data = array();

        if (!$this->userName || !$this->repoName) {
            throw new \Exception(
                'Username and reponame provided via setSourceUrl method are not correct'
            );
        }

        try {
            $data = $this->client->api('repo')->releases()->latest(
                $this->userName,
                $this->repoName
            );
        } catch (\Exception $e) {
            $this->logger->error(
                'UserName: '.$this->userName.'. RepoName: '.$this->repoName.
                '. Message: '.$e->getMessage()
            );
        }

        return $data;
    }
}
