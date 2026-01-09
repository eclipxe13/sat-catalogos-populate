<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogosPopulate\Database;

abstract class AbstractDataField implements DataFieldInterface
{
    /** @var callable(scalar):scalar */
    private $transformFunction;

    /**
     * @param (callable(scalar):scalar)|null $transformFunction
     */
    public function __construct(private readonly string $name, callable|null $transformFunction = null)
    {
        if (null === $transformFunction) {
            $transformFunction = [$this, 'defaultTransformFunction'];
        }
        $this->transformFunction = $transformFunction;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function transform($input)
    {
        /** @var scalar $value */
        $value = call_user_func($this->transformFunction, $input);
        return $value;
    }

    /** @param scalar $input */
    private function defaultTransformFunction($input): string
    {
        return trim((string) $input);
    }
}
