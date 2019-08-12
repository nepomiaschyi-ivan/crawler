<?php
declare(strict_types=1);

namespace App\Service;


use App\Response\ParsePageResponseInterface;

/**
 * Interface ParsePageServiceInterface
 * @package App\Service
 */
interface ParsePageServiceInterface
{
    /**
     * @param string $url
     * @return ParsePageResponseInterface
     */
    public function execute(string $url): ParsePageResponseInterface;
}