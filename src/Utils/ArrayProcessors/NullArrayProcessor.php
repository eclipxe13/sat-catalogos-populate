<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogosPopulate\Utils\ArrayProcessors;

final class NullArrayProcessor implements ArrayProcessorInterface
{
    /** @inheritdoc */
    public function execute(array $array): array
    {
        return $array;
    }
}
