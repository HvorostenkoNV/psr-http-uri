<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\UriFactory;
use HNV\Http\UriTests\CombinationsProvider\FullString\ParsedParts\{
    FullStringWithParsedParts as FullStringWithParsedPartsProvider,
};
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
    public function createUri(
        string $uriString,
        string $scheme,
        string $userInfo,
        string $host,
        int $port,
        string $authority,
        string $path,
        string $query,
        string $fragment,
        string $uriStringNormalized
    ): void {
        $uri                  = (new UriFactory())->createUri($uriString);
        $schemeCaught         = $uri->getScheme();
        $userInfoCaught       = $uri->getUserInfo();
        $hostCaught           = $uri->getHost();
        $portExpected         = $port !== 0 ? $port : null;
        $portCaught           = $uri->getPort();
        $authorityCaught      = $uri->getAuthority();
        $pathCaught           = $uri->getPath();
        $queryCaught          = $uri->getQuery();
        $fragmentCaught       = $uri->getFragment();
        $uriToStringConverted = (string) $uri;

        static::assertSame($scheme, $schemeCaught);
        static::assertSame($userInfo, $userInfoCaught);
        static::assertSame($host, $hostCaught);
        static::assertSame($portExpected, $portCaught);
        static::assertSame($authority, $authorityCaught);
        static::assertSame($path, $pathCaught);
        static::assertSame($query, $queryCaught);
        static::assertSame($fragment, $fragmentCaught);
        static::assertSame($uriStringNormalized, $uriToStringConverted);
    }

    #[Attributes\Test]
    #[Attributes\DataProvider('dataProviderInvalidUri')]
    public function createUriThrowsException(string $uriString): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new UriFactory())->createUri($uriString);

        static::fail("expects exception with URI [{$uriString}]");
    }

    public function dataProviderUriParsedToParts(): array
    {
        $result = [];

        foreach (FullStringWithParsedPartsProvider::get() as $data) {
            if ($data['isValid']) {
                $result[] = [
                    $data['value'],
                    $data['scheme'],
                    $data['userInfo'],
                    $data['host'],
                    $data['port'],
                    $data['authority'],
                    $data['path'],
                    $data['query'],
                    $data['fragment'],
                    $data['valueNormalized'],
                ];
            }
        }

        return $result;
    }

    public function dataProviderInvalidUri(): array
    {
        $result = [];

        foreach (FullStringWithParsedPartsProvider::get() as $data) {
            if (!$data['isValid']) {
                $result[] = [$data['value']];
            }
        }

        return $result;
    }
}
