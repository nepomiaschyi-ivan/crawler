<?php
declare(strict_types=1);

namespace App\Service;


use App\Entity\ParsedPageInfo;
use App\Response\ParsePageResponseInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class RecursiveParsePagesService
 * @package App\Service
 */
class RecursiveParsePagesService implements RecursiveParsePagesServiceInterface
{
    /**
     * @var ParsePageServiceInterface
     */
    private $parsePageService;

    /**
     * @var DomainServiceInterface
     */
    private $domainService;

    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $visitedPages = [];
    /**
     * @var array
     */
    private $pagesToVisit = [];

    /**
     * RecursiveParsePagesService constructor.
     * @param ParsePageServiceInterface $parsePageService
     * @param DomainServiceInterface $domainService
     * @param EntityManager $entityManager
     */
    public function __construct(
        ParsePageServiceInterface $parsePageService,
        DomainServiceInterface $domainService,
        EntityManager $entityManager
    ) {
        $this->parsePageService = $parsePageService;
        $this->domainService = $domainService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->pagesToVisit[] = $url;
    }

    /**
     * @param int|null $depth
     * @param int|null $pages
     * @return ParsePageResponseInterface[]
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute(int $depth = null, int $pages = null): array
    {
        if ($this->isPagesParsed($pages)) {
            return $this->visitedPages;
        }

        $url = array_pop($this->pagesToVisit);
        $parsePageResponse = $this->parsePageService->execute($url);
        $parsedPageInfo = new ParsedPageInfo();
        $parsedPageInfo->setUrl($parsePageResponse->getParsedUrl());
        $parsedPageInfo->setImages($parsePageResponse->getImagesCount());
        $parsedPageInfo->setExecutionTime($parsePageResponse->getExecutionTime());
        $this->entityManager->persist($parsedPageInfo);
        $this->entityManager->flush();
        $this->visitedPages[] = $parsePageResponse;
        $linksToVisit = $parsePageResponse->getLinksToVisit();
        $notVisitedLinks = $this->getNotVisitedLinks($linksToVisit);
        $notVisitedLinks = $this->getLinksByDepth($notVisitedLinks, $depth);
        $this->pagesToVisit = array_merge($this->pagesToVisit, $notVisitedLinks);

        return $this->execute($depth, $pages);
    }

    /**
     * @param array $linksToVisit
     * @return array
     */
    private function getNotVisitedLinks(array $linksToVisit): array
    {
        $uniqueLinksToVisit = array_unique($linksToVisit);
        $visitedPages = array_map(function (ParsePageResponseInterface $pageResponse) {
            return $pageResponse->getParsedUrl();
        }, $this->visitedPages);

        return array_diff($uniqueLinksToVisit, $visitedPages, $this->pagesToVisit);
    }

    /**
     * @param int|null $pages
     * @return bool
     */
    private function isPagesParsed(int $pages = null): bool
    {
        return $this->isStopParseFromPages($pages) || empty($this->pagesToVisit);
    }

    /**
     * @param int|null $pages
     * @return bool
     */
    private function isStopParseFromPages(int $pages = null): bool
    {
        return null !== $pages && \count($this->visitedPages) >= $pages;
    }

    /**
     * @param array $links
     * @param int|null $depth
     * @return array
     */
    private function getLinksByDepth(array $links, ?int $depth)
    {
        if (null === $depth) {
            return $links;
        }

        return array_filter($links, function ($link) use ($depth) {
            return $this->domainService->getLinkDepth($link) <= $depth;
        });
    }
}