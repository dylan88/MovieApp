<?php

namespace App\Command;

use App\Repository\MovieRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieListCommand extends Command
{
    private const DEFAULT_RESULTS = 5;

    protected static $defaultName = 'app:movie:list';
    protected static $defaultDescription = 'Display list of movies';

    private MovieRepository $repo;

    public function __construct(MovieRepository $repo, string $name = null)
    {
        $this->repo = $repo;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addOption('max-results', null, InputOption::VALUE_OPTIONAL, 'Number of movies to display', self::DEFAULT_RESULTS)
        ;
        $this->setHelp('The <info>%command.name%</info> command list of <comment>movies</comment>');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $max = (int) $input->getOption('max-results');
        $max ??= self::DEFAULT_RESULTS;
        if (!\is_numeric($max)) {
            throw new \RuntimeException(sprintf("max_results should be an int %s given", \gettype($max)));
        }

        $movies = $this->repo->findLatest($max);
        $io->table(['id', 'title', 'country', 'price']);

        return Command::SUCCESS;
    }
}
