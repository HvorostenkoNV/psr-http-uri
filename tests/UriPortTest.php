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
     * @covers       Uri::withPort
     * @dataProvider dataProviderNormalizedValues
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
     * @covers       Uri::getPort
     * @dataProvider dataProviderNormalizedValues
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
     * @covers       Uri::getPort
     * @dataProvider dataProviderSchemeWithStandardPorts
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
     * @covers       Uri::withPort
     * @dataProvider dataProviderNormalizedValues
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
     * @covers       Uri::withPort
     * @dataProvider dataProviderInvalidValues
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
     * @covers       Uri::withPort
     * @dataProvider dataProviderValidWithInvalidValues
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
