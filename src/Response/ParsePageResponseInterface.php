<?php

namespace App\Response;

interface ParsePageResponseInterface
{
    public function getParsedUrl(): string;

    public function getImagesCount(): int;

    public function getLinksToVisit(): array;

    public function getExecutionTime(): float;
}