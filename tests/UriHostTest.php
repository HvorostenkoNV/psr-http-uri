<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\Generator\{
    Host\InvalidValuesGenerator,
    Host\NormalizedValuesGenerator,
    InvalidValuesGeneratorInterface,
    NormalizedValuesGeneratorInterface,
};
use InvalidArgumentException;
use PHPUnit\Framework\Attributes;

use function spl_object_id;

/**
 * @internal
 */
#[Attributes\CoversClass(Uri::class)]
#[Attributes\Medium]
class UriHostTest extends AbstractUriTestCase
{
    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function withHostProvidesNewInstance(string $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withHost($value);
        $uriThird  = $uriSecond->withHost($value);

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
    public function withHost(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withHost($value)->getHost();

        static::assertSame($valueNormalized, $valueCaught);
    }

    #[Attributes\Test]
    public function getHostOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getHost();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function getHostAfterClear(string $value): void
    {
        $valueCaught = (new Uri())
            ->withHost($value)
            ->withHost('')
            ->getHost();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderInvalidValues')]
    public function withHostThrowsException(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withHost($value);

        static::fail("expects exception with host [{$value}]");
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderValidWithInvalidValues')]
    public function exceptionThrowingDoesntClearsPreviousValue(
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

        static::assertSame($valueNormalized, $valueCaught);
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
