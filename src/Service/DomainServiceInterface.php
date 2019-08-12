<?php
declare(strict_types=1);

namespace App\Service;


/**
 * Interface DomainServiceInterface
 * @package App\Service
 */
interface DomainServiceInterface
{
    /**
     * @param string $url
     * @return string|null
     */
    public function getDomainFromUrl(string $url): ?string;

    /**
     * @param array $links
     * @param string $domain
     * @return array
     */
    public function getLinksToGo(array $links, string $domain): array;

    /**
     * @param string $url
     * @return bool
     */
    public function isRelativeUrl(string $url): bool;

    /**
     * @param string $link
     * @param string $domain
     * @return bool
     */
    public function isLinkFromDomain(string $link, string $domain): bool;

    /**
     * @param string $url
     * @param string $domain
     * @return string
     */
    public function relativeToAbsoluteUrl(string $url, string $domain): string;

    /**
     * @param string $url
     * @return int
     */
    public function getLinkDepth(string $url): int;
}