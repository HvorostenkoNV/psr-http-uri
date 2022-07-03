<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\{
    Collection\SchemeStandardPorts,
    Uri,
};
use HNV\Http\UriTests\ValuesProvider\Port as PortValuesProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function spl_object_id;

/**
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with port values.
 *
 * @internal
 * @covers Uri
 * @small
 */
class UriPortTest extends TestCase
{
    /**
     * Test "Uri::withPort" provides new instance of URI.
     *
     * @covers       Uri::withPort
     * @dataProvider dataProviderNormalizedValues
     *
     * @param int $value value
     */
    public function testProvidesNewInstance(int $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withPort($value);
        $uriThird  = $uriSecond->withPort($value);

        static::assertNotSame(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withPort\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
        static::assertNotSame(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withPort\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
    }

    /**
     * Test "Uri::getPort" provides valid normalized value.
     *
     * @covers       Uri::getPort
     * @dataProvider dataProviderNormalizedValues
     *
     * @param int $value           value
     * @param int $valueNormalized normalized value
     */
    public function testGetValue(int $value, int $valueNormalized): void
    {
        $valueCaught = (new Uri())->withPort($value)->getPort();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withPort->getPort\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expected result is \"{$valueNormalized}\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::getPort" provides expects value from empty object.
     *
     * @covers Uri::getPort
     */
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getPort();

        static::assertNull(
            $valueCaught,
            "Action \"Uri->getPort\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::getPort" provides null if port is standard for given scheme.
     *
     * @covers       Uri::getPort
     * @dataProvider dataProviderSchemeWithStandardPorts
     *
     * @param string $scheme scheme
     * @param int    $port   standard port fo this scheme
     */
    public function testGetValueWithStandardPort(string $scheme, int $port): void
    {
        $valueCaught = (new Uri())
            ->withScheme($scheme)
            ->withPort($port)
            ->getPort();

        static::assertNull(
            $valueCaught,
            "Action \"Uri->withScheme->withPort->getPort\" returned unexpected result.\n".
            "Action was called with parameters (scheme => {$scheme}, port => {$port}).\n".
            "Expected result is \"null\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::withPort" clears value on setting empty string.
     *
     * @covers       Uri::withPort
     * @dataProvider dataProviderNormalizedValues
     *
     * @param int $value value
     */
    public function testClearValue(int $value): void
    {
        $valueCaught = (new Uri())
            ->withPort($value)
            ->withPort()
            ->getPort();

        static::assertNull(
            $valueCaught,
            "Action \"Uri->withPort->withPort->getPort\" returned unexpected result.\n".
            "Action was called with parameters (value => {$value}, value => \"0\").\n".
            "Expected result is \"null\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::withPort" throws exception with invalid argument.
     *
     * @covers       Uri::withPort
     * @dataProvider dataProviderInvalidValues
     *
     * @param int $value invalid value
     */
    public function testThrowsException(int $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withPort($value);

        static::fail(
            "Action \"Uri->withPort\" threw no expected exception.\n".
            "Action was called with parameters (value => {$value}).\n".
            "Expects \"InvalidArgumentException\" exception.\n".
            'Caught no exception.'
        );
    }

    /**
     * Test "Uri::withPort" saves previous value with setting new invalid value.
     *
     * @covers       Uri::withPort
     * @dataProvider dataProviderValidWithInvalidValues
     *
     * @param int $value           valid value
     * @param int $valueNormalized normalized valid value
     * @param int $invalidValue    invalid value
     */
    public function testSavesPreviousValueOnError(
        int $value,
        int $valueNormalized,
        int $invalidValue
    ): void {
        $uri = (new Uri())->withPort($value);

        try {
            $uri->withPort($invalidValue);
        } catch (InvalidArgumentException) {
        }

        $valueCaught = $uri->getPort();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withPort->withPort->getPort\" returned unexpected result.\n".
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

        foreach (PortValuesProvider::getValidValues() as $value => $valueNormalized) {
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

        foreach (PortValuesProvider::getInvalidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    /**
     * Data provider: schemes with standard ports.
     *
     * @return array data
     */
    public function dataProviderSchemeWithStandardPorts(): array
    {
        $result = [];

        foreach (SchemeStandardPorts::cases() as $case) {
            foreach ($case->ports() as $port) {
                $result[] = [$case->value, $port];
            }
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
