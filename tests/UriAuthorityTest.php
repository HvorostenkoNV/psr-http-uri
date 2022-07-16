<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\CombinationsProvider\Authority\CombinedValue as AuthorityCombinationsProvider;
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
    public function getAuthority(
        string $scheme,
        string $login,
        string $password,
        string $host,
        int $port,
        string $valueNormalized
    ): void {
        $uri = (new Uri())
            ->withScheme($scheme)
            ->withUserInfo($login, $password);

        try {
            $uri = $uri->withHost($host);
        } catch (InvalidArgumentException) {
        }

        try {
            $uri = $uri->withPort($port);
        } catch (InvalidArgumentException) {
        }

        $valueCaught = $uri->getAuthority();

        static::assertSame($valueNormalized, $valueCaught);
    }

    #[Attributes\Test]
    public function getAuthorityOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getAuthority();

        static::assertSame('', $valueCaught);
    }

    public function dataProviderAuthorityByParts(): array
    {
        $result = [];

        foreach (AuthorityCombinationsProvider::get() as $combination) {
            $result[] = [
                $combination['scheme'],
                $combination['login'],
                $combination['password'],
                $combination['host'],
                $combination['port'],
                $combination['value'],
            ];
        }

        return $result;
    }
}
