<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\Generator\Authority\CombinedDataGenerator;
use HNV\Http\UriTests\ValueObject\Authority\CombinedData as AuthorityCombinedData;
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
class UriAuthorityTest extends TestCase
{
    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderAuthorityByParts')]
    public function getAuthority(AuthorityCombinedData $data): void
    {
        $uri = (new Uri())
            ->withScheme($data->scheme)
            ->withUserInfo($data->userLogin, $data->userPassword);

        try {
            $uri = $uri->withHost($data->host);
        } catch (InvalidArgumentException) {
        }

        try {
            $uri = $uri->withPort($data->port);
        } catch (InvalidArgumentException) {
        }

        $valueCaught = $uri->getAuthority();

        static::assertSame($data->fullValue, $valueCaught);
    }

    #[Attributes\Test]
    public function getAuthorityOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getAuthority();

        static::assertSame('', $valueCaught);
    }

    public function dataProviderAuthorityByParts(): iterable
    {
        foreach ((new CombinedDataGenerator())->generate() as $combination) {
            yield [$combination];
        }
    }
}
