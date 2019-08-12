<?php
declare(strict_types=1);

namespace App\Service;


use App\Response\ParsePageResponse;
use App\Response\ParsePageResponseInterface;
use PHPHtmlParser\Dom;

/**
 * Class ParsePageService
 * @package App\Service
 */
class ParsePageService implements ParsePageServiceInterface
{
    /**
     * @var Dom
     */
    private $dom;
    /**
     * @var ImageServiceInterface
     */
    private $imageService;
    /**
     * @var DomainServiceInterface
     */
    private $domainService;


    /**
     * ParsePageService constructor.
     * @param Dom $dom
     * @param DomainServiceInterface $domainService
     * @param ImageServiceInterface $imageService
     *      */
    public function __construct(
        Dom $dom,
        DomainServiceInterface $domainService,
        ImageServiceInterface $imageService
    ) {
        $this->dom = $dom;
        $this->domainService = $domainService;
        $this->imageService = $imageService;

    }

    /**
     * @param string $url
     * @return ParsePageResponseInterface
     */
    public function execute($url): ParsePageResponseInterface
    {
        $startExecuteTime = microtime(true);
        $this->dom->loadFromUrl($url, [], new Curl());
        $links = $this->dom->find('a')->toArray();
        $images = $this->dom->find('img')->toArray();
        $domain = $this->domainService->getDomainFromUrl($url);
        $validImages = $this->imageService->getImagesFromDomain($images, $domain);
        $validLinks = $this->domainService->getLinksToGo($links, $domain);
        $endExecuteTime = microtime(true);
        $executionTime = $endExecuteTime - $startExecuteTime;

        return new ParsePageResponse($url, count($validImages), $validLinks, $executionTime);
    }
}