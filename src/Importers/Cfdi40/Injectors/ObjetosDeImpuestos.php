<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogosPopulate\Importers\Cfdi40\Injectors;

use PhpCfdi\SatCatalogosPopulate\AbstractCsvInjector;
use PhpCfdi\SatCatalogosPopulate\Database\DataFields;
use PhpCfdi\SatCatalogosPopulate\Database\DataTable;
use PhpCfdi\SatCatalogosPopulate\Database\DateDataField;
use PhpCfdi\SatCatalogosPopulate\Database\PaddingDataField;
use PhpCfdi\SatCatalogosPopulate\Database\TextDataField;
use PhpCfdi\SatCatalogosPopulate\Utils\CsvFile;
use RuntimeException;

class ObjetosDeImpuestos extends AbstractCsvInjector
{
    public function checkHeaders(CsvFile $csv): void
    {
        $csv->move(3);
        $expected = ['c_ObjetoImp', 'Descripción', 'Fecha inicio de vigencia', 'Fecha fin de vigencia'];
        $headers = $csv->readLine();

        if ($expected !== $headers) {
            throw new RuntimeException("The headers did not match on file {$this->sourceFile()}");
        }

        $csv->next();
    }

    public function dataTable(): DataTable
    {
        return new DataTable('cfdi_40_objetos_impuestos', new DataFields([
            new PaddingDataField('id', '0', 2),
            new TextDataField('texto'),
            new DateDataField('vigencia_desde'),
            new DateDataField('vigencia_hasta'),
        ]));
    }
}
