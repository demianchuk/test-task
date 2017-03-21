<?php

namespace AppBundle\Twig;

use Twig_Environment;
use Twig_Extension;

/**
 * Class ProgressBarWidgetExtension.
 *
 * @author Sergii Demianchuk <demianchuk.sergii@gmail.com>
 */
class ProgressBarWidgetExtension extends Twig_Extension
{
    const TYPE_CLASS_GREEN = 'green';
    const TYPE_CLASS_YELLOW = 'yellow';
    const TYPE_CLASS_RED = 'red';

    const YELLOW_BARRIER = 30;
    const GREEN_BARRIER = 70;

    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('progress_bar_widget', [$this, 'renderProgressBarWidget']),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'progress_bar_widget';
    }

    /**
     * Render progress bar.
     *
     * @param int $score
     */
    public function renderProgressBarWidget($score, $text = null)
    {
        $typeClass = self::TYPE_CLASS_RED;

        if ($score >= self::YELLOW_BARRIER) {
            $typeClass = self::TYPE_CLASS_YELLOW;
        }

        if ($score >= self::GREEN_BARRIER) {
            $typeClass = self::TYPE_CLASS_GREEN;
        }

        $this->twig->display('AppBundle:TwigExtension:progress_bar_widget.html.twig', [
            'score' => $score,
            'typeClass' => $typeClass,
            'text' => $text
        ]);
    }
}
