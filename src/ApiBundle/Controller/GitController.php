<?php

namespace ApiBundle\Controller;

use AppBundle\Entity\FOS\User;
use ApiBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * @RouteResource("statistic")
 */
class GitController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of statistical info for repository with it's score.
     *
     * @ApiDoc(
     *  section="Git statistic info",
     *  resource=true,
     *  description="Git statistic info with it's score",
     *  statusCodes={
     *         200="Returned when successful",
     *         400="Returned when bad request"
     *  }
     * )
     * @Get("/statistics/{userName}/{repoName}")
     * @param string $userName
     * @param string $repoName
     *
     * @return Response
     */
    public function getAllStatisticsAction($userName, $repoName)
    {
        //Uncomment that for testing OAuth
        /*if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }*/

        try {
            $gitService = $this->get('app.git_hub_service');
            $gitService->setSourceAsNames($userName, $repoName);

            $statistics = $gitService->getAllGithubStatistics();

            if (is_null($statistics->getStarsNumber())) {
                throw new \Exception('Bad request');
            }

            $scoreService = $this->get('app.score_service');
            $score = $scoreService->calculateScore($statistics);

            $data = array(
                'stars' => $statistics->getStarsNumber(),
                'forks' => $statistics->getForksNumber(),
                'subscribers' => $statistics->getSubscribersNumber(),
                'closed_pull_requests' => $statistics->getClosedPullRequestNumber(),
                'open_pull_requests' => $statistics->getOpenPullRequestNumber(),
                'last_release_date' => $statistics->getLastReleaseDate(),
                'last_update_date' => $statistics->getLastUpdateDate(),
                'last_pull_request_merge_date' => $statistics->getLastPullRequestMergeDate(),
                'score' => $score
            );
            $view = $this->view($data);

            return $this->handleView($view);
        } catch (\Exception $e) {
            $view = $this->view(['status' => 'Bad request'], 400);

            return $this->handleView($view);
        }
    }
}
