<?php

namespace AppBundle\DependencyInjection\GitHub;

use Github\Client as GithubClient;
use Cache\Adapter\Predis\PredisCachePool;
use Predis\Client as Predis;
use AppBundle\DependencyInjection\GitHub\GitHubService;
use Psr\Log\LoggerInterface;

/**
 * Class GitHubServiceFactory.
 *
 * @author Sergii Demianchuk <demianchuk.sergii@gmail.com>
 */
class GitHubServiceFactory
{
    /**
     * @param boolean $useRedisPoll
     * @param string $redisHost
     * @param LoggerInterface $logger
     *
     * @return GitHubServiceInterface
     */
    public static function getGitHubService($useRedisPool, $redisHost, LoggerInterface $logger)
    {
        $gitHubClient = new GithubClient();

        if ($useRedisPool) {
            $redisClient = new Predis($redisHost);
            $pool = new PredisCachePool($redisClient);
            $gitHubClient->addCache($pool);
        }

        $gitHubService = new GitHubService($gitHubClient, $logger);

        return $gitHubService;
    }
}
