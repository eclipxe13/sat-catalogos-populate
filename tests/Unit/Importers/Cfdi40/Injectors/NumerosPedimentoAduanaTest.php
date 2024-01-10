<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogosPopulate\Tests\Unit\Importers\Cfdi40\Injectors;

use PhpCfdi\SatCatalogosPopulate\AbstractCsvInjector;
use PhpCfdi\SatCatalogosPopulate\Database\DateDataField;
use PhpCfdi\SatCatalogosPopulate\Database\IntegerDataField;
use PhpCfdi\SatCatalogosPopulate\Database\TextDataField;
use PhpCfdi\SatCatalogosPopulate\Importers\Cfdi40\Injectors\NumerosPedimentoAduana;
use PhpCfdi\SatCatalogosPopulate\InjectorInterface;
use PhpCfdi\SatCatalogosPopulate\Tests\TestCase;
use PhpCfdi\SatCatalogosPopulate\Utils\CsvFile;
use PHPUnit\Framework\Attributes\TestWith;
use RuntimeException;

class NumerosPedimentoAduanaTest extends TestCase
{
    private string $sourceFile;

    private NumerosPedimentoAduana $injector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sourceFile = $this->utilFilePath('cfdi40/c_NumPedimentoAduana.csv');
        $this->injector = new NumerosPedimentoAduana($this->sourceFile);
    }

    public function testNumerosPedimentoAduanaExtendsAbstractCsvInjector(): void
    {
        $this->assertInstanceOf(AbstractCsvInjector::class, $this->injector);
        $this->assertInstanceOf(InjectorInterface::class, $this->injector);
    }

    public function testCheckHeadersOnValidSource(): void
    {
        $csv = new CsvFile($this->sourceFile);
        $this->injector->checkHeaders($csv);

        $this->assertSame(4, $csv->position(), 'The csv position is on the first content line');
    }

    public function testCheckHeadersOnInvalidSource(): void
    {
        $csv = new CsvFile($this->utilFilePath('sample.csv'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The headers did not match on file');
        $this->injector->checkHeaders($csv);
    }

    public function testDataTableFields(): void
    {
        $dataTable = $this->injector->dataTable();
        $this->assertSame('cfdi_40_numeros_pedimento_aduana', $dataTable->name());
        $expectedClasses = [
            'aduana' => TextDataField::class,
            'patente' => TextDataField::class,
            'ejercicio' => IntegerDataField::class,
            'cantidad' => IntegerDataField::class,
            'vigencia_desde' => DateDataField::class,
            'vigencia_hasta' => DateDataField::class,
        ];
        $this->assertSame(array_keys($expectedClasses), $dataTable->fields()->keys());
        foreach ($expectedClasses as $key => $classname) {
            $this->assertInstanceOf($classname, $dataTable->fields()->get($key));
        }
        $this->assertSame([], $dataTable->primaryKey());
    }

    #[TestWith(['AB', 'AB'])]
    #[TestWith(['', '00'])]
    #[TestWith(['9', '09'])]
    public function testTransformAduana(string $value, string $expected): void
    {
        $dataTable = $this->injector->dataTable();
        $input = [0 => $value];
        $transform = $dataTable->fields()->transform($input);
        $this->assertSame($expected, $transform['aduana']);
    }

    #[TestWith(['ABCD', 'ABCD'])]
    #[TestWith(['', '0000'])]
    #[TestWith(['9', '0009'])]
    public function testTransformPatente(string $value, string $expected): void
    {
        $dataTable = $this->injector->dataTable();
        $input = [1 => $value];
        $transform = $dataTable->fields()->transform($input);
        $this->assertSame($expected, $transform['patente']);
    }

    #[TestWith(['0', 0])]
    #[TestWith(['', 0])]
    #[TestWith(['2018', 2018])]
    public function testTransformEjercicio(string $value, int $expected): void
    {
        $dataTable = $this->injector->dataTable();
        $input = [2 => $value];
        $transform = $dataTable->fields()->transform($input);
        $this->assertSame($expected, $transform['ejercicio']);
    }

    #[TestWith(['0', 0])]
    #[TestWith(['', 0])]
    #[TestWith(['2018', 2018])]
    public function testTransformCantidad(string $value, int $expected): void
    {
        $dataTable = $this->injector->dataTable();
        $input = [3 => $value];
        $transform = $dataTable->fields()->transform($input);
        $this->assertSame($expected, $transform['cantidad']);
    }
}
