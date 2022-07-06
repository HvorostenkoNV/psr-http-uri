<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\ValuesProvider\Scheme as SchemeValuesProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function spl_object_id;

/**
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with scheme values.
 *
 * @internal
 * @covers Uri
 * @small
 */
class UriSchemeTest extends TestCase
{
    /**
     * @covers       Uri::withScheme
     * @dataProvider dataProviderNormalizedValues
     */
    public function testProvidesNewInstance(string $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withScheme($value);
        $uriThird  = $uriSecond->withScheme($value);

        static::assertNotSame(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withScheme\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
        static::assertNotSame(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withScheme\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
    }

    /**
     * @covers       Uri::getScheme
     * @dataProvider dataProviderNormalizedValues
     */
    public function testGetValue(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withScheme($value)->getScheme();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withScheme->getScheme\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expected result is \"{$valueNormalized}\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * @covers Uri::getScheme
     */
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getScheme();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->getScheme\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * @covers       Uri::withScheme
     * @dataProvider dataProviderNormalizedValues
     */
    public function testClearValue(string $value): void
    {
        $valueCaught = (new Uri())
            ->withScheme($value)
            ->withScheme('')
            ->getScheme();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->withScheme->withScheme->getScheme\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}, value => \"empty string\").\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * @covers       Uri::withScheme
     * @dataProvider dataProviderInvalidValues
     */
    public function testThrowsException(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withScheme($value);

        static::fail(
            "Action \"Uri->withScheme\" threw no expected exception.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expects \"InvalidArgumentException\" exception.\n".
            'Caught no exception.'
        );
    }

    /**
     * @covers       Uri::withScheme
     * @dataProvider dataProviderValidWithInvalidValues
     */
    public function testSavesPreviousValueOnError(
        string $value,
        string $valueNormalized,
        string $invalidValue
    ): void {
        $uri = (new Uri())->withScheme($value);

        try {
            $uri->withScheme($invalidValue);
        } catch (InvalidArgumentException) {
        }

        $valueCaught = $uri->getScheme();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withScheme->withScheme->getScheme\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value} (valid value),".
            " value => {$invalidValue} (invalid value)).\n".
            "Expected result is \"{$valueNormalized}\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Data provider: values with their normalized pairs.
     */
    public function dataProviderNormalizedValues(): array
    {
        $result = [];

        foreach (SchemeValuesProvider::getValidValues() as $value => $valueNormalized) {
            $result[] = [$value, $valueNormalized];
        }

        return $result;
    }

    /**
     * Data provider: invalid values.
     */
    public function dataProviderInvalidValues(): array
    {
        $result = [];

        foreach (SchemeValuesProvider::getInvalidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    /**
     * Data provider: valid values (with their normalized pairs) with invalid values.
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
