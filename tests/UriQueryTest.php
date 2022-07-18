<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\Generator\{
    InvalidValuesGeneratorInterface,
    NormalizedValuesGeneratorInterface,
    Query\InvalidValuesGenerator,
    Query\NormalizedValuesGenerator,
};
use PHPUnit\Framework\Attributes;

use function spl_object_id;

/**
 * @internal
 */
#[Attributes\CoversClass(Uri::class)]
#[Attributes\Small]
class UriQueryTest extends AbstractUriTestCase
{
    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function withQueryProvidesNewInstance(string $value): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withQuery($value);
        $uriThird  = $uriSecond->withQuery($value);

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
    public function getQuery(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withQuery($value)->getQuery();

        static::assertSame($valueNormalized, $valueCaught);
    }

    #[Attributes\Test]
    public function getQueryOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getQuery();

        static::assertSame('', $valueCaught);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderNormalizedValues')]
    public function getQueryAfterClear(string $value): void
    {
        $valueCaught = (new Uri())
            ->withQuery($value)
            ->withQuery('')
            ->getQuery();

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
