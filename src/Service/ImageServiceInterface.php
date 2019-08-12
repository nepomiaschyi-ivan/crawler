<?php
declare(strict_types=1);

namespace App\Service;


/**
 * Interface ImageServiceInterface
 * @package App\Service
 */
interface ImageServiceInterface
{
    /**
     * @param array $images
     * @param string $domain
     * @return mixed
     */
    public function getImagesFromDomain(array $images, string $domain);
}