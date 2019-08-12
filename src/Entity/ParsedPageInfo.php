<?php
declare(strict_types=1);

namespace App\Entity;


class ParsedPageInfo
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $url;
    /**
     * @var int
     */
    private $images;
    /**
     * @var float
     */
    private $executionTime;


    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getImages(): int
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images): void
    {
        $this->images = $images;
    }

    /**
     * @return mixed
     */
    public function getExecutionTime(): float
    {
        return $this->executionTime;
    }

    /**
     * @param mixed $executionTime
     */
    public function setExecutionTime($executionTime): void
    {
        $this->executionTime = $executionTime;
    }
}