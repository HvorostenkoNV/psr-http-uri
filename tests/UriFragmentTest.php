<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\ValuesProvider\Fragment as FragmentValuesProvider;
use PHPUnit\Framework\TestCase;

use function spl_object_id;

/**
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with fragment values.
 *
 * @internal
 * @covers Uri
 * @small
 */
class UriFragmentTest extends TestCase
{
    /**
     * @covers       Uri::withFragment
     * @dataProvider dataProviderNormalizedValues
     */
    public function testProvidesNewInstance(string $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withFragment($value);
        $uriThird  = $uriSecond->withFragment($value);

        static::assertNotSame(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withFragment\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
        static::assertNotSame(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withFragment\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
    }

    /**
     * @covers       Uri::getFragment
     * @dataProvider dataProviderNormalizedValues
     */
    public function testGetValue(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withFragment($value)->getFragment();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withFragment->getFragment\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expected result is \"{$valueNormalized}\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * @covers Uri::getFragment
     */
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getFragment();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->getFragment\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * @covers       Uri::withFragment
     * @dataProvider dataProviderNormalizedValues
     */
    public function testClearValue(string $value): void
    {
        $valueCaught = (new Uri())
            ->withFragment($value)
            ->withFragment('')
            ->getFragment();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->withFragment->withFragment->getFragment\" returned unexpected result.\n".
            "Action was called with parameters (login => {$value}, login => \"empty string\").\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * @covers       Uri::withFragment
     * @dataProvider dataProviderValidWithInvalidValues
     */
    public function testClearValueWithInvalidData(
        string $validValue,
        string $invalidValue
    ): void {
        $valueCaught = (new Uri())
            ->withFragment($validValue)
            ->withFragment($invalidValue)
            ->getFragment();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->withFragment->withFragment->getFragment\" returned unexpected result.\n".
            "Action was called with parameters (value => {$validValue}, value => {$invalidValue}).\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Data provider: values with their normalized pairs.
     */
    public function dataProviderNormalizedValues(): array
    {
        $result = [];

        foreach (FragmentValuesProvider::getValidValues() as $value => $valueNormalized) {
            $result[] = [(string) $value, $valueNormalized];
        }

        return $result;
    }

    /**
     * Data provider: invalid values.
     */
    public function dataProviderInvalidValues(): array
    {
        $result = [];

        foreach (FragmentValuesProvider::getInvalidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    /**
     * Data provider: valid values with invalid values.
     */
    public function dataProviderValidWithInvalidValues(): array
    {
        $validValues   = $this->dataProviderNormalizedValues();
        $invalidValues = $this->dataProviderInvalidValues();
        $result        = [];

        foreach ($invalidValues as $data) {
            $result[] = [
                $validValues[0][0],
                $validValues[0][1],
                $data[0],
            ];
        }

        return $result;
    }
}
