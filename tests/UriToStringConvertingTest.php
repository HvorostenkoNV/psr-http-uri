<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\CombinationsProvider\FullString\CombinedValue\{
    FullStringCombinations as FullStringCombinationsProvider,
};
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with URI converting to string behavior.
 *
 * @internal
 * @covers Uri
 * @large
 */
class UriToStringConvertingTest extends TestCase
{
    /**
     * @covers       Uri::__toString
     * @dataProvider dataProviderUriByParts
     */
    public function testToStringConverting(
        string $scheme,
        string $login,
        string $password,
        string $host,
        int $port,
        string $path,
        string $query,
        string $fragment,
        string $uriExpected
    ): void {
        $uri = new Uri();

        try {
            $uri = $uri->withScheme($scheme);
        } catch (InvalidArgumentException) {
        }

        $uri = $uri->withUserInfo($login, $password);

        try {
            $uri = $uri->withHost($host);
        } catch (InvalidArgumentException) {
        }

        try {
            $uri = $uri->withPort($port);
        } catch (InvalidArgumentException) {
        }

        try {
            $uri = $uri->withPath($path);
        } catch (InvalidArgumentException) {
        }

        try {
            $uri = $uri->withQuery($query);
        } catch (InvalidArgumentException) {
        }

        $uri       = $uri->withFragment($fragment);
        $uriCaught = (string) $uri;

        static::assertSame(
            $uriExpected,
            $uriCaught,
            'Action "Uri->withScheme->withUserInfo->withHost->withPort'.
            "->withPath->withQuery->withFragment->toString\" returned unexpected result.\n".
            "Action was called with parameters (scheme => {$scheme}, login => {$login},".
            " password => {$password}, host => {$host}, port => {$port}, path => {$path},".
            " query => {$query}, fragment => {$fragment}).\n".
            "Expected result is \"{$uriExpected}\".\n".
            "Caught result is \"{$uriCaught}\"."
        );
    }

    /**
     * Data provider: URI by parts.
     */
    public function dataProviderUriByParts(): array
    {
        $result = [];

        foreach (FullStringCombinationsProvider::get() as $combination) {
            $result[] = [
                $combination['scheme'],
                $combination['login'],
                $combination['password'],
                $combination['host'],
                $combination['port'],
                $combination['path'],
                $combination['query'],
                $combination['fragment'],
                $combination['value'],
            ];
        }

        return $result;
    }
}
