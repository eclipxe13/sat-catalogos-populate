<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogosPopulate\Origins\Reviewers;

use PhpCfdi\SatCatalogosPopulate\Origins\OriginInterface;
use PhpCfdi\SatCatalogosPopulate\Origins\Review;

interface ReviewerInterface
{
    public function accepts(OriginInterface $origin): bool;

    public function review(OriginInterface $origin): Review;
}
