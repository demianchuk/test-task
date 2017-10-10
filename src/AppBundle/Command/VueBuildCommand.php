<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Process\Process;

/**
 * Command to build all vue apps.
 */
class VueBuildCommand extends ContainerAwareCommand
{
    const APP_APPS_DIRECTORY = '@AppBundle/Resources/public/js/vue/apps/';
    const APP_BUILDS_DIRECTORY = '@AppBundle/Resources/public/js/vue/builds/';

    private $vueLocations = [
        'app' => [
            'appsDir' => self::APP_APPS_DIRECTORY,
            'buildsDir' => self::APP_BUILDS_DIRECTORY,
        ]
    ];

    protected function configure()
    {
        $this->setName('clicktrans:build:vue')
            ->setDescription('Builds all Vue apps using Browserify')
            ->addArgument('filename', InputArgument::OPTIONAL, 'Specify Vue app file name', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileLocator = $this->getContainer()->get('file_locator');

        $fileName = ($input->getArgument('filename') ? $input->getArgument('filename') : '*.js');

        foreach ($this->vueLocations as $name => $vueLocation) {
            $appsDir = $fileLocator->locate($vueLocation['appsDir']);

            $finder = new Finder();
            $finder->files()->in($appsDir)->name($fileName);

            if ($finder->count() > 0) {
                $progressBar = new ProgressBar($output, $finder->count());
                $progressBar->setFormat('normal');
                $progressBar->setMessage('<info>Building all Vue apps</info>');
                $progressBar->setRedrawFrequency(1);

                foreach ($finder as $file) {
                    $this->buildVueFile($file, $vueLocation['buildsDir']);
                    $progressBar->advance();
                }

                $progressBar->finish();
                $output->writeln('All '.$name.' Vue apps were built');
            } else {
                $output->writeln('No '.$name.' Vue apps to build');
            }
        }
    }

    /**
     * Execute browserify for app file.
     *
     * @param SplFileInfo $file
     */
    private function buildVueFile(SplFileInfo $file, $location)
    {
        $fileLocator = $this->getContainer()->get('file_locator');

        $command = sprintf(
            'browserify -t vueify -e %s -o %s%s',
            $file->getRealPath(),
            $fileLocator->locate($location),
            $file->getFilename()
        );

        $process = new Process($command);
        $process->setTimeout(60);
        $process->run();
    }
}
