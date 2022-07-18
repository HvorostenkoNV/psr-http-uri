<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\Generator\{
    Fragment\InvalidValuesGenerator,
    Fragment\NormalizedValuesGenerator,
    InvalidValuesGeneratorInterface,
    NormalizedValuesGeneratorInterface,
};
use PHPUnit\Framework\Attributes;

use function spl_object_id;

/**
 * @internal
 */
#[Attributes\CoversClass(Uri::class)]
#[Attributes\Small]
class UriFragmentTest extends AbstractUriTestCase
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

    protected function getNormalizedValuesGenerator(): NormalizedValuesGeneratorInterface
    {
        return new NormalizedValuesGenerator();
    }

    protected function getInvalidValuesGenerator(): InvalidValuesGeneratorInterface
    {
        return new InvalidValuesGenerator();
    }
}
