<?php

namespace Leapt\ElasticaBundle\Command;

use Leapt\ElasticaBundle\Service;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RebuildCommand extends Command
{
    /**
     * @var Service
     */
    protected $elastica;

    public function __construct(Service $service)
    {
        parent::__construct();
        $this->elastica = $service;
    }

    protected function configure(): void
    {
        $this
            ->setName('leapt:elastica:rebuild')
            ->setDescription('Rebuild all elastica indexes')
            ->addArgument('types', InputArgument::IS_ARRAY + InputArgument::OPTIONAL, 'Specific types to rebuild')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $timeStart = microtime(true);

        // Rebuild only given types
        if ($input->hasArgument('types') && 0 < \count($input->getArgument('types'))) {
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

        return 0;
    }
}
