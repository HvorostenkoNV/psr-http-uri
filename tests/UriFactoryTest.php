<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\UriFactory;
use HNV\Http\UriTests\Generator\FullString\ParsedDataGenerator;
use HNV\Http\UriTests\ValueObject\FullString\ParsedData as FullStringParsedData;
use InvalidArgumentException;
use PHPUnit\Framework\{
    Attributes,
    TestCase,
};

/**
 * @internal
 */
#[Attributes\CoversClass(UriFactory::class)]
#[Attributes\Large]
class UriFactoryTest extends TestCase
{
    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderUriParsedToParts')]
    public function createUri(FullStringParsedData $data): void
    {
        $uri            = (new UriFactory())->createUri($data->valueToParse);
        $portExpected   = $data->port !== 0 ? $data->port : null;

        static::assertSame($data->scheme, $uri->getScheme());
        static::assertSame($data->userInfo, $uri->getUserInfo());
        static::assertSame($data->host, $uri->getHost());
        static::assertSame($portExpected, $uri->getPort());
        static::assertSame($data->authority, $uri->getAuthority());
        static::assertSame($data->path, $uri->getPath());
        static::assertSame($data->query, $uri->getQuery());
        static::assertSame($data->fragment, $uri->getFragment());
        static::assertSame($data->valueNormalized, (string) $uri);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderInvalidUri')]
    public function createUriThrowsException(string $uriString): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new UriFactory())->createUri($uriString);

        static::fail("expects exception with URI [{$uriString}]");
    }

    public function dataProviderUriParsedToParts(): iterable
    {
        foreach ((new ParsedDataGenerator())->generate() as $combination) {
            if ($combination->isValid) {
                yield [$combination];
            }
        }
    }

    public function dataProviderInvalidUri(): iterable
    {
        foreach ((new ParsedDataGenerator())->generate() as $combination) {
            if (!$combination->isValid) {
                yield [$combination->valueToParse];
            }
        }
    }
}
