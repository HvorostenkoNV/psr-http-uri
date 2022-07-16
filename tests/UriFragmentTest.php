<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\ValuesProvider\Fragment as FragmentValuesProvider;
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
class UriFragmentTest extends TestCase
{
    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function withFragmentProvidesNewInstance(string $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withFragment($value);
        $uriThird  = $uriSecond->withFragment($value);

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
    public function withFragment(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withFragment($value)->getFragment();

        static::assertSame($valueNormalized, $valueCaught);
    }

    #[Attributes\Test]
    public function getFragmentOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getFragment();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function getFragmentAfterClear(string $value): void
    {
        $valueCaught = (new Uri())
            ->withFragment($value)
            ->withFragment('')
            ->getFragment();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderValidWithInvalidValues')]
    public function withFragmentWithInvalidValueClearsPreviousValue(
        string $validValue,
        string $invalidValue
    ): void {
        $valueCaught = (new Uri())
            ->withFragment($validValue)
            ->withFragment($invalidValue)
            ->getFragment();

        static::assertSame('', $valueCaught);
    }

    public function dataProviderNormalizedValues(): array
    {
        $result = [];

        foreach (FragmentValuesProvider::getValidValues() as $value => $valueNormalized) {
            $result[] = [(string) $value, $valueNormalized];
        }

        return $result;
    }

    public function dataProviderInvalidValues(): array
    {
        $result = [];

        foreach (FragmentValuesProvider::getInvalidValues() as $value) {
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
