<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogosPopulate\Tests\Features\Origins\Reviewers;

use PhpCfdi\SatCatalogosPopulate\Origins\ResourcesGatewayInterface;
use PhpCfdi\SatCatalogosPopulate\Origins\Reviewers\Nomina12eReviewer;
use PhpCfdi\SatCatalogosPopulate\Tests\TestCase;

final class Nomina12eReviewerTest extends TestCase
{
    public function testReviewerCanObtainTheUrlToDownload(): void
    {
        $gateway = $this->createMock(ResourcesGatewayInterface::class);
        $reviewer = new Nomina12eReviewer($gateway);
        $url = $reviewer->obtainResourceUrl();
        $this->assertMatchesRegularExpression('#^https://.*/cat_Nomina.*xls$#', $url);
    }
}
