<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogosPopulate\Importers\Cfdi40\Injectors;

use PhpCfdi\SatCatalogosPopulate\AbstractCsvInjector;
use PhpCfdi\SatCatalogosPopulate\Database\BoolDataField;
use PhpCfdi\SatCatalogosPopulate\Database\DataFields;
use PhpCfdi\SatCatalogosPopulate\Database\DataTable;
use PhpCfdi\SatCatalogosPopulate\Database\DateDataField;
use PhpCfdi\SatCatalogosPopulate\Database\TextDataField;
use PhpCfdi\SatCatalogosPopulate\InjectorInterface;
use PhpCfdi\SatCatalogosPopulate\Utils\CsvFile;
use RuntimeException;

use function PhpCfdi\SatCatalogosPopulate\Utils\array_rtrim;

class UsosCfdi extends AbstractCsvInjector implements InjectorInterface
{
    public function checkHeaders(CsvFile $csv): void
    {
        $csv->move(3);
        $expectedLines = [
            [
                'c_UsoCFDI',
                'Descripción',
                'Aplica para tipo persona',
                '',
                'Fecha inicio de vigencia',
                'Fecha fin de vigencia',
                'Regímen Fiscal Receptor',
            ],
            [
                '',
                '',
                'Física',
                'Moral',
            ],
        ];

        foreach ($expectedLines as $expected) {
            $headers = array_rtrim($csv->readLine());

            if ($expected !== $headers) {
                throw new RuntimeException("The headers did not match on file {$this->sourceFile()}");
            }
            $csv->next();
        }
    }

    public function dataTable(): DataTable
    {
        return new DataTable('cfdi_40_usos_cfdi', new DataFields([
            new TextDataField('id'),
            new TextDataField('texto'),
            new BoolDataField('aplica_fisica', ['Sí']),
            new BoolDataField('aplica_moral', ['Sí']),
            new DateDataField('vigencia_desde'),
            new DateDataField('vigencia_hasta'),
            new TextDataField('regimenes_fiscales_receptores'), // TODO: use CsvListDataField
        ]));
    }
}
