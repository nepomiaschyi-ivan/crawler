<?php
declare(strict_types=1);

namespace App\Service;


use PHPHtmlParser\Dom\HtmlNode;

/**
 * Class DomainService
 * @package App\Service
 */
class DomainService implements DomainServiceInterface
{
    /**
     * @var array
     */
    private $whiteListExtension = [
        '.html'
    ];

    /**
     * @param string $url
     * @return string|null
     */
    public function getDomainFromUrl(string $url): ?string
    {
        $parsedUrl = parse_url($url);

        return isset($parsedUrl['scheme'], $parsedUrl['host']) ? $parsedUrl['scheme'] . '://' . $parsedUrl['host'] : null;
    }

    /**
     * @param array $links
     * @param string $domain
     * @return array
     */
    public function getLinksToGo(array $links, string $domain): array
    {
        $linksHref = [];

        foreach ($links as $link) {
            /**
             * @var HtmlNode $link
             */
            $tag = $link->getTag();
            $url = $tag->getAttribute('href')['value'];
            if (!$url) {
                continue;
            }
            if ($this->isRelativeUrl($url)) {
                $url = $this->relativeToAbsoluteUrl($url, $domain);
            }
            if ($this->isLinkFromDomain($url, $domain) && !$this->isLinkAnchor($url) && !$this->isLinkFile($url)) {
                $linksHref[] = $url;
            }
        }

        return array_unique($linksHref);
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isRelativeUrl(string $url): bool
    {
        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['host'])) {
            return false;
        }

        return isset($parsedUrl['path']) && strlen($parsedUrl['path']) > 1;
    }

    /**
     * @param string $link
     * @param string $domain
     * @return bool
     */
    public function isLinkFromDomain(string $link, string $domain): bool
    {
        $domainToCheck = $this->getDomainFromUrl($link);

        if (!$domainToCheck) {
            return false;
        }

        return $domain === $domainToCheck;
    }

    /**
     * @param string $url
     * @param string $domain
     * @return string
     */
    public function relativeToAbsoluteUrl(string $url, string $domain): string
    {
        $parsedUrl = parse_url($domain);

        if (!isset($parsedUrl['host']) || !isset($parsedUrl['scheme'])) {
            throw new \InvalidArgumentException('invalid domain');
        }

        $url = substr($url, 0, 1) === '/' ? $url : '/' . $url;

        return $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $url;
    }

    /**
     * @param string $url
     * @return int
     */
    public function getLinkDepth(string $url): int
    {
        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['scheme'], $parsedUrl['host']) && !isset($parsedUrl['path'])) {
            return 0;
        }

        if (!isset($parsedUrl['path'])) {
            throw new \InvalidArgumentException('invalid url');
        }

        $path = $this->isLastCharSlash($parsedUrl['path'])
            ? rtrim($parsedUrl['path'], '/')
            : $parsedUrl['path'];

        $explodedPath = explode('/', $path);

        return count($explodedPath);
    }

    /**
     * @param string $url
     * @return bool
     */
    private function isLinkAnchor(string $url): bool
    {
        $parsedUrl = parse_url($url);

        return isset($parsedUrl['fragment']);
    }

    /**
     * @param string $ur
     * @return bool
     */
    private function isLinkFile(string $ur): bool
    {
        $parsedUrl = parse_url($ur);

        if (!isset($parsedUrl['path'])) {
            return false;
        }

        $dotPos = mb_strpos($parsedUrl['path'], '.');

        if (!$dotPos) {
            return false;
        }

        $extension = mb_substr($parsedUrl['path'], $dotPos);

        return $this->isValidExtension($extension);
    }

    /**
     * @param string $extension
     * @return bool
     */
    private function isValidExtension(string $extension): bool
    {
        return !in_array($extension, $this->getWhiteListExtension());
    }

    /**
     * @return array
     */
    private function getWhiteListExtension(): array
    {
        return $this->whiteListExtension;
    }

    /**
     * @param string $path
     * @return bool
     */
    private function isLastCharSlash(string $path): bool
    {
        return mb_substr($path, -1, 1) === '/';
    }
}