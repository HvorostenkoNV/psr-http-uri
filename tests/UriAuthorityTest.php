<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\CombinationsProvider\Authority\CombinedValue as AuthorityCombinationsProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with authority values.
 *
 * @internal
 * @covers Uri
 * @medium
 */
class UriAuthorityTest extends TestCase
{
    /**
     * @covers       Uri::getAuthority
     * @dataProvider dataProviderAuthorityByParts
     */
    public function testGetValue(
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

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            'Action "Uri->withScheme->withUserInfo->withHost->withPort->getAuthority"'.
            " returned unexpected result.\n".
            "Action was called with parameters (scheme => {$scheme}, login => {$login},".
            " password => {$password}, host => {$host}, port => {$port}).\n".
            "Expected result is \"{$valueNormalized}\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * @covers Uri::getAuthority
     */
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getAuthority();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->getAuthority\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Data provider: authority by parts.
     */
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
