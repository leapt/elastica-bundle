<?php

namespace Leapt\ElasticaBundle\Command;

use Leapt\ElasticaBundle\Service;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RebuildCommand
 * @package Leapt\ElasticaBundle\Command
 */
class RebuildCommand extends Command
{
    /** @var Service */
    protected $elastica;

    protected function configure()
    {
        $this
            ->setName('leapt:elastica:rebuild')
            ->setDescription('Rebuild all elastica indexes')
            ->addArgument('types', InputArgument::IS_ARRAY + InputArgument::OPTIONAL, 'Specific types to rebuild')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeStart = microtime(true);

        // Rebuild only given types
        if ($input->hasArgument('types') && 0 < count($input->getArgument('types'))) {
            foreach ($input->getArgument('types') as $type) {
                $output->writeln(sprintf('Rebuilding "%s" type', $type));
                $this->elastica->rebuildType($type);
            }
        }

        // Rebuild all types
        else {
            $output->writeln('Rebuilding all elastica indexes');
            $this->elastica->createIndexes();
            $this->elastica->reindex();
        }

        $time = number_format(microtime(true) - $timeStart, 3);
        $output->writeln('Rebuilt indexes in ' . $time . ' seconds.');
    }

    /**
     * @param Service $elastica
     * @required
     */
    public function setElasticaService(Service $elastica): void
    {
        $this->elastica = $elastica;
    }
}