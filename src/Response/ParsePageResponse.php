<?php
declare(strict_types=1);

namespace App\Response;


/**
 * Class ParsePageResponse
 * @package App\Response
 */
class ParsePageResponse implements ParsePageResponseInterface
{
    /**
     * @var string
     */
    private $parsedUrl;
    /**
     * @var int
     */
    private $imagesCount = 0;
    /**
     * @var array
     */
    private $linkToVisit = [];

    /**
     * @var int
     */
    private $executionTime = 0;

    /**
     * ParsePageResponse constructor.
     * @param string $parsedUrl
     * @param int $imagesCount
     * @param array $linkToVisit
     * @param float $executionTime
     */
    public function __construct(string $parsedUrl, int $imagesCount, array $linkToVisit, float $executionTime)
    {
        $this->parsedUrl = $parsedUrl;
        $this->imagesCount = $imagesCount;
        $this->linkToVisit = $linkToVisit;
        $this->executionTime = $executionTime;
    }

    public function getParsedUrl(): string
    {
        return $this->parsedUrl;
    }

    /**
     * @return int
     */
    public function getImagesCount(): int
    {
        return $this->imagesCount;
    }

    /**
     * @return array
     */
    public function getLinksToVisit(): array
    {
        return $this->linkToVisit;
    }

    /**
     * @return float
     */
    public function getExecutionTime(): float
    {
        return $this->executionTime;
    }

}