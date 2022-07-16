<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\ValuesProvider\Path as PathValuesProvider;
use InvalidArgumentException;
use PHPUnit\Framework\{
    Attributes,
    TestCase,
};

use function spl_object_id;

/**
 * @internal
 */
#[Attributes\CoversClass(Uri::class)]
#[Attributes\Medium]
class UriPathTest extends TestCase
{
    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function withPathProvidesNewInstance(string $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withPath($value);
        $uriThird  = $uriSecond->withPath($value);

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
    public function withPath(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withPath($value)->getPath();

        static::assertSame($valueNormalized, $valueCaught);
    }

    #[Attributes\Test]
    public function getPathOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getPath();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function getPathAfterClear(string $value): void
    {
        $valueCaught = (new Uri())
            ->withPath($value)
            ->withPath('')
            ->getPath();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderInvalidValues')]
    public function withPathThrowsException(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withPath($value);

        static::fail("expects exception with path [{$value}]");
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderValidWithInvalidValues')]
    public function exceptionThrowingDoesntClearsPreviousValue(
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

        static::assertSame($valueNormalized, $valueCaught);
    }

    public function dataProviderNormalizedValues(): array
    {
        $result = [];

        foreach (PathValuesProvider::getValidValues() as $value => $valueNormalized) {
            $result[] = [$value, $valueNormalized];
        }

        return $result;
    }

    public function dataProviderInvalidValues(): array
    {
        $result = [];

        foreach (PathValuesProvider::getInvalidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

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
