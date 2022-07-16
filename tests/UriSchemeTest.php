<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\ValuesProvider\Scheme as SchemeValuesProvider;
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
class UriSchemeTest extends TestCase
{
    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function withSchemeProvidesNewInstance(string $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withScheme($value);
        $uriThird  = $uriSecond->withScheme($value);

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
    public function getScheme(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withScheme($value)->getScheme();

        static::assertSame($valueNormalized, $valueCaught);
    }

    #[Attributes\Test]
    public function getSchemeOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getScheme();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function getSchemeAfterClear(string $value): void
    {
        $valueCaught = (new Uri())
            ->withScheme($value)
            ->withScheme('')
            ->getScheme();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderInvalidValues')]
    public function withSchemeThrowsException(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withScheme($value);

        static::fail("expects exception with scheme [{$value}]");
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderValidWithInvalidValues')]
    public function exceptionThrowingDoesntClearsPreviousValue(
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

        static::assertSame($valueNormalized, $valueCaught);
    }

    public function dataProviderNormalizedValues(): array
    {
        $result = [];

        foreach (SchemeValuesProvider::getValidValues() as $value => $valueNormalized) {
            $result[] = [$value, $valueNormalized];
        }

        return $result;
    }

    public function dataProviderInvalidValues(): array
    {
        $result = [];

        foreach (SchemeValuesProvider::getInvalidValues() as $value) {
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
