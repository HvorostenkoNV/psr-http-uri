<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\Generator\FullString\CombinedDataGenerator;
use HNV\Http\UriTests\ValueObject\FullString\CombinedData as FullStringCombinedData;
use InvalidArgumentException;
use PHPUnit\Framework\{
    Attributes,
    TestCase,
};

/**
 * @internal
 */
#[Attributes\CoversClass(Uri::class)]
#[Attributes\Large]
class UriToStringConvertingTest extends TestCase
{
    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderUriByParts')]
    public function toStringCast(FullStringCombinedData $data): void
    {
        $uri = new Uri();

        try {
            $uri = $uri->withScheme($data->scheme);
        } catch (InvalidArgumentException) {
        }

        $uri = $uri->withUserInfo($data->userLogin, $data->userPassword);

        try {
            $uri = $uri->withHost($data->host);
        } catch (InvalidArgumentException) {
        }

        try {
            $uri = $uri->withPort($data->port);
        } catch (InvalidArgumentException) {
        }

        try {
            $uri = $uri->withPath($data->path);
        } catch (InvalidArgumentException) {
        }

        try {
            $uri = $uri->withQuery($data->query);
        } catch (InvalidArgumentException) {
        }

        $uri = $uri->withFragment($data->fragment);

        static::assertSame($data->fullValue, (string) $uri);
    }

    public function dataProviderUriByParts(): iterable
    {
        foreach ((new CombinedDataGenerator())->generate() as $combination) {
            yield [$combination];
        }
    }
}
