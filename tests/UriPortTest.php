<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\{
    Collection\SchemeRules,
    Uri,
};
use HNV\Http\UriTests\Generator\{
    InvalidValuesGeneratorInterface,
    NormalizedValuesGeneratorInterface,
    Port\InvalidValuesGenerator,
    Port\NormalizedValuesGenerator,
};
use InvalidArgumentException;
use PHPUnit\Framework\Attributes;

use function spl_object_id;

/**
 * @internal
 */
#[Attributes\CoversClass(Uri::class)]
#[Attributes\Small]
class UriPortTest extends AbstractUriTestCase
{
    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function withPortProvidesNewInstance(int $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withPort($value);
        $uriThird  = $uriSecond->withPort($value);

        static::assertNotSame(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            'Expects instance not the same'
        );
        static::assertNotSame(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            'Expects instance not the same'
        );
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function withPort(int $value, int $valueNormalized): void
    {
        $valueCaught = (new Uri())->withPort($value)->getPort();

        static::assertSame($valueNormalized, $valueCaught);
    }

    #[Attributes\Test]
    public function getPortOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getPort();

        static::assertNull($valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderSchemeWithStandardPorts')]
    public function getPortWithStandardPort(string $scheme, int $port): void
    {
        $valueCaught = (new Uri())
            ->withScheme($scheme)
            ->withPort($port)
            ->getPort();

        static::assertNull($valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function getPortAfterClear(int $value): void
    {
        $valueCaught = (new Uri())
            ->withPort($value)
            ->withPort()
            ->getPort();

        static::assertNull($valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderInvalidValues')]
    public function withPortThrowsException(int $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withPort($value);

        static::fail("expects exception with port [{$value}]");
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderValidWithInvalidValues')]
    public function exceptionThrowingDoesntClearsPreviousValue(
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

        static::assertSame($valueNormalized, $valueCaught);
    }

    public function dataProviderSchemeWithStandardPorts(): array
    {
        $result = [];

        foreach (SchemeRules::STANDARD_PORTS as $scheme => $ports) {
            foreach ($ports as $port) {
                $result[] = [$scheme, $port];
            }
        }

        return $result;
    }

    protected function getNormalizedValuesGenerator(): NormalizedValuesGeneratorInterface
    {
        return new NormalizedValuesGenerator();
    }

    protected function getInvalidValuesGenerator(): InvalidValuesGeneratorInterface
    {
        return new InvalidValuesGenerator();
    }
}
