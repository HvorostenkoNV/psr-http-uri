<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\ValuesProvider\Query as QueryValuesProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function spl_object_id;

/**
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with query values.
 *
 * @internal
 * @covers Uri
 * @small
 */
class UriQueryTest extends TestCase
{
    /**
     * Test "Uri::withQuery" provides new instance of URI.
     *
     * @covers       Uri::withQuery
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $value value
     */
    public function testProvidesNewInstance(string $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withQuery($value);
        $uriThird  = $uriSecond->withQuery($value);

        static::assertNotSame(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withQuery\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
        static::assertNotSame(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withQuery\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
    }

    /**
     * Test "Uri::getQuery" provides valid normalized value.
     *
     * @covers       Uri::getQuery
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $value           value
     * @param string $valueNormalized normalized value
     */
    public function testGetValue(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withQuery($value)->getQuery();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withQuery->getQuery\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expected result is \"{$valueNormalized}\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::getQuery" provides expects value from empty object.
     *
     * @covers Uri::getQuery
     */
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getQuery();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->getQuery\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::withQuery" clears value on setting empty string.
     *
     * @covers       Uri::withQuery
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $value value
     */
    public function testClearValue(string $value): void
    {
        $valueCaught = (new Uri())
            ->withQuery($value)
            ->withQuery('')
            ->getQuery();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->withQuery->withQuery->getQuery\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}, value => \"empty string\").\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::withQuery" throws exception with invalid argument.
     *
     * @covers       Uri::withQuery
     * @dataProvider dataProviderInvalidValues
     *
     * @param string $value invalid value
     */
    public function testThrowsException(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withQuery($value);

        static::fail(
            "Action \"Uri->withQuery\" threw no expected exception.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expects \"InvalidArgumentException\" exception.\n".
            'Caught no exception.'
        );
    }

    /**
     * Test "Uri::withQuery" saves previous value with setting new invalid value.
     *
     * @covers       Uri::withQuery
     * @dataProvider dataProviderValidWithInvalidValues
     *
     * @param string $value           valid value
     * @param string $valueNormalized normalized valid value
     * @param string $invalidValue    invalid value
     */
    public function testSavesPreviousValueOnError(
        string $value,
        string $valueNormalized,
        string $invalidValue
    ): void {
        $uri = (new Uri())->withQuery($value);

        try {
            $uri->withQuery($invalidValue);
        } catch (InvalidArgumentException) {
        }

        $valueCaught = $uri->getQuery();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withQuery->withQuery->getQuery\" returned unexpected result.\n".
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

        foreach (QueryValuesProvider::getValidValues() as $value => $valueNormalized) {
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

        foreach (QueryValuesProvider::getInvalidValues() as $value) {
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
