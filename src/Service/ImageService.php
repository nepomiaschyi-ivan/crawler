<?php
declare(strict_types=1);

namespace App\Service;


use PHPHtmlParser\Dom\HtmlNode;

/**
 * Class ImageService
 * @package App\Service
 */
class ImageService implements ImageServiceInterface
{
    private $domainService;

    public function __construct(DomainServiceInterface $domainService)
    {
        $this->domainService = $domainService;
    }

    /**
     * @param HtmlNode[] $images
     * @param string $domain
     * @return array
     */
    public function getImagesFromDomain(array $images, string $domain)
    {
        $imagesSrc = [];

        foreach ($images as $image) {
            if(!$image->tag->hasAttribute('src')) {
                continue;
            }

            $src = $image->tag->getAttribute('src')['value'];
            if (!$src) {
                continue;
            }
            if ($this->domainService->isRelativeUrl($src) || $this->domainService->isLinkFromDomain($src, $domain)) {
                $imagesSrc[] = $image->tag->getAttribute('src')['value'];
            }
        }

        return $imagesSrc;
    }
}