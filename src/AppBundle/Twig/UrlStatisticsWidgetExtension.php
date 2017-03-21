<?php

namespace AppBundle\Twig;

use Twig_Environment;
use Twig_Extension;
use AppBundle\Interfaces\GitHubStatisticModelInterface;
use AppBundle\DependencyInjection\GitHub\CalculateScoreService;

/**
 * Class UrlStatisticsWidgetExtension.
 *
 * @author Sergii Demianchuk <demianchuk.sergii@gmail.com>
 */
class UrlStatisticsWidgetExtension extends Twig_Extension
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var CalculateScoreService
     */
    private $calculateScoreService;

    public function __construct(Twig_Environment $twig, CalculateScoreService $calculateScoreService)
    {
        $this->twig = $twig;
        $this->calculateScoreService = $calculateScoreService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('url_statistics_widget', [$this, 'renderUrlStatisticsWidget']),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'url_statistics_widget';
    }

    /**
     * Render widget with url statistics info.
     *
     * @param GitHubStatisticModelInterface $objectStatistic
     * @param string $url
     * @param Category $category
     */
    public function renderUrlStatisticsWidget(GitHubStatisticModelInterface $objectStatistic, $url)
    {
        $this->twig->display('AppBundle:TwigExtension:url_statistics_widget.html.twig', [
            'objectStatistic' => $objectStatistic,
            'urlLink' => $url,
            'score' => $this->calculateScoreService->calculateScore($objectStatistic)
        ]);
    }
}
