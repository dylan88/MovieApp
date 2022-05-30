<?php

namespace App\Command;

use App\Repository\MovieRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieImportCommand extends Command
{
    protected static $defaultName = 'app:movie:import';
    protected static $defaultDescription = 'import movie from api';

    private MovieRepository $repo;
    public function __construct(MovieRepository $repo, string $name = null)
    {
        $this->repo = $repo;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('title', InputArgument::REQUIRED, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $title = $input->getArgument('title');
        $movie = $this->repo->findMovieByTitleFromApi($title);
        if (!$movie) {
            $io->error("This title does not exist in IMDB");
            return Command::FAILURE;
        }
        $this->repo->add($movie);

        $io->success('You have a upload');

        return Command::SUCCESS;
    }
}
