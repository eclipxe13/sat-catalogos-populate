<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogosPopulate\Tests\Unit\Importers;

use PhpCfdi\SatCatalogosPopulate\Importers\Nomina\Injectors\Estados;
use PhpCfdi\SatCatalogosPopulate\Importers\NominaEstadosCatalogs;
use PhpCfdi\SatCatalogosPopulate\InjectorInterface;
use PhpCfdi\SatCatalogosPopulate\Tests\TestCase;

final class NominaEstadosCatalogsTest extends TestCase
{
    /**
     * @see NominaCatalogs::createInjectors()
     */
    public function testContainsAllAndOnlyThisInjectorsByDefault(): void
    {
        $expectedInjectorsClasses = [
            Estados::class,
        ];

        $importer = new NominaEstadosCatalogs();
        $injectors = $importer->createInjectors('');

        $injectorsClasses = array_map(fn (InjectorInterface $item): string => $item::class, $injectors->all());

        $this->assertEquals(array_replace_recursive($injectorsClasses, $expectedInjectorsClasses), $injectorsClasses);
        $this->assertCount(count($expectedInjectorsClasses), $injectorsClasses);
    }
}
