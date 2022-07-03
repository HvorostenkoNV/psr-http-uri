<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\ValuesProvider\Host as HostValuesProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Throwable;

use function spl_object_id;

/**
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with host values.
 *
 * @internal
 * @covers Uri
 * @medium
 */
class UriHostTest extends TestCase
{
    /**
     * Test "Uri::withHost" provides new instance of URI.
     *
     * @covers       Uri::withHost
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $value value
     *
     * @throws Throwable
     */
    public function testProvidesNewInstance(string $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withHost($value);
        $uriThird  = $uriSecond->withHost($value);

        static::assertNotSame(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withHost\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
        static::assertNotSame(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withHost\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
    }

    /**
     * Test "Uri::getHost" provides valid normalized value.
     *
     * @covers       Uri::getHost
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $value           value
     * @param string $valueNormalized normalized value
     *
     * @throws Throwable
     */
    public function testGetValue(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withHost($value)->getHost();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withHost->getHost\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expected result is \"{$valueNormalized}\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::getHost" provides expects value from empty object.
     *
     * @covers Uri::getHost
     *
     * @throws Throwable
     */
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getHost();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->getHost\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::withHost" clears value on setting empty string.
     *
     * @covers       Uri::withHost
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $value value
     *
     * @throws Throwable
     */
    public function testClearValue(string $value): void
    {
        $valueCaught = (new Uri())
            ->withHost($value)
            ->withHost('')
            ->getHost();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->withHost->withHost->getHost\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}, value => \"empty string\").\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::withHost" throws exception with invalid argument.
     *
     * @covers       Uri::withHost
     * @dataProvider dataProviderInvalidValues
     *
     * @param string $value invalid value
     *
     * @throws Throwable
     */
    public function testThrowsException(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withHost($value);

        static::fail(
            "Action \"Uri->withHost\" threw no expected exception.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expects \"InvalidArgumentException\" exception.\n".
            'Caught no exception.'
        );
    }

    /**
     * Test "Uri::withHost" saves previous value with setting new invalid value.
     *
     * @covers       Uri::withHost
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
        $uri = (new Uri())->withHost($value);

        try {
            $uri->withHost($invalidValue);
        } catch (InvalidArgumentException) {
        }

        $valueCaught = $uri->getHost();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withHost->withHost->getHost\" returned unexpected result.\n".
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

        foreach (HostValuesProvider::getValidValues() as $value => $valueNormalized) {
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

        foreach (HostValuesProvider::getInvalidValues() as $value) {
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
