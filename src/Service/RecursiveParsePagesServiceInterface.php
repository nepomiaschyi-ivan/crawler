<?php
declare(strict_types=1);

namespace App\Service;


use App\Response\ParsePageResponseInterface;

/**
 * Interface RecursiveParsePagesServiceInterface
 * @package App\Service
 */
interface RecursiveParsePagesServiceInterface
{
    /**
     * @param string $url
     */
    public function setUrl(string $url): void;

    /**
     * @param int|null $depth
     * @param int|null $pages
     * @return ParsePageResponseInterface[]
     */
    public function execute(int $depth = null, int $pages = null);
}