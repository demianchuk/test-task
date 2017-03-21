<?php

namespace AppBundle\Controller;

use AppBundle\Form\GitHubUrlCompareType;
use AppBundle\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Statistic\RepoScoreStatistic;
use AppBundle\Form\GitHubUrlCompareVueType;

/**
 * @author Sergii Demianchuk <demianchuk.sergii@gmail.com>
 */
class DefaultController extends Controller
{
    /**
     * Back end version of task
     *
     * @Route("/", name="homepage")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repoStatistic = new RepoScoreStatistic();
        $compareForm = $this->createForm(GitHubUrlCompareType::class, $repoStatistic);

        $compareForm->handleRequest($request);

        if ($compareForm->isSubmitted() && $compareForm->isValid()) {
            $gitService = $this->get('app.git_hub_service');
            $repoStatistic = $compareForm->getData();

            $gitService->setSourceUrl($repoStatistic->getUrlFirst());
            $firstUrlStatistic = $gitService->getAllGithubStatistics();

            $gitService->setSourceUrl($repoStatistic->getUrlSecond());
            $secondUrlStatistic = $gitService->getAllGithubStatistics();

            $scoreService = $this->get('app.score_service');
            $repoStatistic->setScoreFirst($scoreService->calculateScore($firstUrlStatistic));
            $repoStatistic->setScoreSecond($scoreService->calculateScore($secondUrlStatistic));

            $em->persist($repoStatistic);
            $em->flush();
        }

        $lastRepoStatistics = $em->getRepository('AppBundle:Statistic\RepoScoreStatistic')
            ->getLastRecords();

        return $this->render("AppBundle:Homepage:index.html.twig", [
            'compareForm' => $compareForm->createView(),
            'repoStatistic' => $repoStatistic,
            'secondUrlStatistic' => $secondUrlStatistic ?? null,
            'firstUrlStatistic' => $firstUrlStatistic ?? null,
            'lastRepoStatistics' => $lastRepoStatistics
        ]);
    }

    /**
     * Front end version of task with using vue
     *
     * @Route("/vue", name="vue")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function vueAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $compareForm = $this->createForm(GitHubUrlCompareVueType::class);

        return $this->render("AppBundle:Vue:index.html.twig", [
            'compareForm' => $compareForm->createView()
        ]);
    }
}
