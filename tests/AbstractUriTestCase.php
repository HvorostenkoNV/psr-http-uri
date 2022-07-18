<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\UriTests\Generator\{
    InvalidValuesGeneratorInterface,
    NormalizedValuesGeneratorInterface,
};
use PHPUnit\Framework\TestCase;

abstract class AbstractUriTestCase extends TestCase
{
    public function dataProviderNormalizedValues(): iterable
    {
        foreach ($this->getNormalizedValuesGenerator()->generate() as $value) {
            yield [$value->value, $value->valueNormalized];
        }
    }

    public function dataProviderInvalidValues(): iterable
    {
        foreach ($this->getInvalidValuesGenerator()->generate() as $value) {
            yield [$value->value];
        }
    }

    public function dataProviderValidWithInvalidValues(): iterable
    {
        $validValue             = null;
        $validValueNormalized   = null;

        foreach ($this->dataProviderNormalizedValues() as $data) {
            $validValue           = $data[0];
            $validValueNormalized = $data[1];
            break;
        }

        foreach ($this->dataProviderInvalidValues() as $data) {
            yield [
                $validValue,
                $validValueNormalized,
                $data[0],
            ];
        }
    }

    abstract protected function getNormalizedValuesGenerator(): NormalizedValuesGeneratorInterface;

    abstract protected function getInvalidValuesGenerator(): InvalidValuesGeneratorInterface;
}
