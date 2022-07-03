<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\ValuesProvider\Path as PathValuesProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Throwable;

use function spl_object_id;

/**
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with path values.
 *
 * @internal
 * @covers Uri
 * @small
 */
class UriPathTest extends TestCase
{
    /**
     * Test "Uri::withPath" provides new instance of URI.
     *
     * @covers       Uri::withPath
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $value value
     *
     * @throws Throwable
     */
    public function testProvidesNewInstance(string $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withPath($value);
        $uriThird  = $uriSecond->withPath($value);

        static::assertNotSame(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withPath\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
        static::assertNotSame(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withPath\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
    }

    /**
     * Test "Uri::getPath" provides valid normalized value.
     *
     * @covers       Uri::getPath
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $value           value
     * @param string $valueNormalized normalized value
     *
     * @throws Throwable
     */
    public function testGetValue(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withPath($value)->getPath();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withPath->getPath\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expected result is \"{$valueNormalized}\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::getPath" provides expects value from empty object.
     *
     * @covers Uri::getPath
     *
     * @throws Throwable
     */
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getPath();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->getPath\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::withPath" clears value on setting empty string.
     *
     * @covers       Uri::withPath
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $value value
     *
     * @throws Throwable
     */
    public function testClearValue(string $value): void
    {
        $valueCaught = (new Uri())
            ->withPath($value)
            ->withPath('')
            ->getPath();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->withPath->withPath->getPath\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}, value => \"empty string\").\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::withPath" throws exception with invalid argument.
     *
     * @covers       Uri::withPath
     * @dataProvider dataProviderInvalidValues
     *
     * @param string $value invalid value
     *
     * @throws Throwable
     */
    public function testThrowsException(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withPath($value);

        static::fail(
            "Action \"Uri->withPath\" threw no expected exception.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expects \"InvalidArgumentException\" exception.\n".
            'Caught no exception.'
        );
    }

    /**
     * Test "Uri::withPath" saves previous value with setting new invalid value.
     *
     * @covers       Uri::withPath
     * @dataProvider dataProviderValidWithInvalidValues
     *
     * @param string $value           valid value
     * @param string $valueNormalized normalized valid value
     * @param string $invalidValue    invalid value
     *
     * @throws Throwable
     */
    public function testSavesPreviousValueOnError(
        string $value,
        string $valueNormalized,
        string $invalidValue
    ): void {
        $uri = (new Uri())->withPath($value);

        try {
            $uri->withPath($invalidValue);
        } catch (InvalidArgumentException) {
        }

        $valueCaught = $uri->getPath();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withPath->withPath->getPath\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value} (valid value),".
            " value => {$invalidValue} (invalid value)).\n".
            "Expected result is \"{$valueNormalized}\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Data provider: values with their normalized pairs.
     *
     * @return array data
     */
    public function dataProviderNormalizedValues(): array
    {
        $result = [];

        foreach (PathValuesProvider::getValidValues() as $value => $valueNormalized) {
            $result[] = [$value, $valueNormalized];
        }

        return $result;
    }

    /**
     * Data provider: invalid values.
     *
     * @return array data
     */
    public function dataProviderInvalidValues(): array
    {
        $result = [];

        foreach (PathValuesProvider::getInvalidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    /**
     * Data provider: valid values (with their normalized pairs) with invalid values.
     *
     * @return array data
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
