<?php
declare(strict_types=1);

namespace App\Command;

use App\Response\ParsePageResponseInterface;
use App\Service\RecursiveParsePagesServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CrawlerCommand extends Command
{
    protected static $defaultName = 'app:parse';
    private $recursiveParsePage;

    public function __construct(RecursiveParsePagesServiceInterface $recursiveParsePage, string $name = null)
    {
        parent::__construct($name);

        $this->recursiveParsePage = $recursiveParsePage;
    }

    protected function configure()
    {
        $this->setDescription('Parse images from site')
            ->setHelp('Parse images. Required parameter site url.');
        $this->addArgument('url');
        $this->addOption('depth', 'd', InputOption::VALUE_OPTIONAL);
        $this->addOption('page', 'p', InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        $io = new SymfonyStyle($input, $output);
        if (!$url) {
            $io->error('Missing required argument url');
            die();
        }

        $io->writeln('start parsing page');

        $depth = $input->getOption('depth');
        $page = $input->getOption('page');

        if ($depth) {
            $depth = (int)trim($depth, '=\'" ');
        }
        if ($page) {
            $page = (int)trim($page, '=\'" ');
        }

        $this->recursiveParsePage->setUrl($url);
        $response = $this->recursiveParsePage->execute($depth, $page);
        $tableValues = array_map(function(ParsePageResponseInterface $pageResponse) {
            return [
                $pageResponse->getParsedUrl(),
                $pageResponse->getImagesCount(),
                $pageResponse->getExecutionTime()
            ];
        }, $response);
        $io->table(['page', 'images', 'executionTime'], $tableValues);
        $io->writeln('parsed pages: ' . count($tableValues));
    }
}