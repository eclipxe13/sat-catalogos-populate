<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogosPopulate\Tests\Unit\Importers;

use PhpCfdi\SatCatalogosPopulate\Importers\Rep\Injectors\TiposCadenaPago;
use PhpCfdi\SatCatalogosPopulate\Importers\RepCatalogs;
use PhpCfdi\SatCatalogosPopulate\InjectorInterface;
use PhpCfdi\SatCatalogosPopulate\Tests\TestCase;

final class RepCatalogsTest extends TestCase
{
    /**
     * @see RepCatalogs::createInjectors()
     */
    public function testContainsAllAndOnlyThisImportersByDefault(): void
    {
        $expectedInjectorsClasses = [
            TiposCadenaPago::class,
        ];

        $importer = new RepCatalogs();
        $injectors = $importer->createInjectors('');

        $injectorsClasses = array_map(fn (InjectorInterface $item): string => $item::class, $injectors->all());

        $this->assertEquals(array_replace_recursive($injectorsClasses, $expectedInjectorsClasses), $injectorsClasses);
        $this->assertCount(count($expectedInjectorsClasses), $injectorsClasses);
    }
}
