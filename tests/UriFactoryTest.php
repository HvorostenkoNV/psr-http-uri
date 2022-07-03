<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\UriFactory;
use HNV\Http\UriTests\CombinationsProvider\FullString\ParsedParts\{
    FullStringWithParsedParts as FullStringWithParsedPartsProvider,
};
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * PSR-7 UriFactoryInterface implementation test.
 *
 * Testing working URI string parsing behavior.
 *
 * @internal
 * @covers Uri
 * @large
 */
class UriFactoryTest extends TestCase
{
    /**
     * Test UriFactory parses string in expected way.
     *
     * @covers       UriFactory::createUri
     * @dataProvider dataProviderUriParsedToParts
     *
     * @param string $uriString           full URI string
     * @param string $scheme              scheme expected
     * @param string $userInfo            user info expected
     * @param string $host                host expected
     * @param int    $port                port expected
     * @param string $authority           authority expected
     * @param string $path                path expected
     * @param string $query               query expected
     * @param string $fragment            fragment expected
     * @param string $uriStringNormalized full URI string in normalized state
     */
    public function testParsesString(
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

        static::assertSame(
            $scheme,
            $schemeCaught,
            "Action \"UriFactory->createUri->getScheme\" returned unexpected result.\n".
            "Action was called with parameters (uri => {$uriString}).\n".
            "Expected result is \"{$scheme}\".\n".
            "Caught result is \"{$schemeCaught}\"."
        );
        static::assertSame(
            $userInfo,
            $userInfoCaught,
            "Action \"UriFactory->createUri->getUserInfo\" returned unexpected result.\n".
            "Action was called with parameters (uri => {$uriString}).\n".
            "Expected result is \"{$userInfo}\".\n".
            "Caught result is \"{$userInfoCaught}\"."
        );
        static::assertSame(
            $host,
            $hostCaught,
            "Action \"UriFactory->createUri->getHost\" returned unexpected result.\n".
            "Action was called with parameters (uri => {$uriString}).\n".
            "Expected result is \"{$host}\".\n".
            "Caught result is \"{$hostCaught}\"."
        );
        static::assertSame(
            $portExpected,
            $portCaught,
            "Action \"UriFactory->createUri->getPort\" returned unexpected result.\n".
            "Action was called with parameters (uri => {$uriString}).\n".
            "Expected result is \"{$portExpected}\".\n".
            "Caught result is \"{$portCaught}\"."
        );
        static::assertSame(
            $authority,
            $authorityCaught,
            "Action \"UriFactory->createUri->getAuthority\" returned unexpected result.\n".
            "Action was called with parameters (uri => {$uriString}).\n".
            "Expected result is \"{$authority}\".\n".
            "Caught result is \"{$authorityCaught}\"."
        );
        static::assertSame(
            $path,
            $pathCaught,
            "Action \"UriFactory->createUri->getPath\" returned unexpected result.\n".
            "Action was called with parameters (uri => {$uriString}).\n".
            "Expected result is \"{$path}\".\n".
            "Caught result is \"{$pathCaught}\"."
        );
        static::assertSame(
            $query,
            $queryCaught,
            "Action \"UriFactory->createUri->getQuery\" returned unexpected result.\n".
            "Action was called with parameters (uri => {$uriString}).\n".
            "Expected result is \"{$query}\".\n".
            "Caught result is \"{$queryCaught}\"."
        );
        static::assertSame(
            $fragment,
            $fragmentCaught,
            "Action \"UriFactory->createUri->getFragment\" returned unexpected result.\n".
            "Action was called with parameters (uri => {$uriString}).\n".
            "Expected result is \"{$fragment}\".\n".
            "Caught result is \"{$fragmentCaught}\"."
        );

        static::assertSame(
            $uriStringNormalized,
            $uriToStringConverted,
            "Action \"UriFactory->createUri->toString\" returned unexpected result.\n".
            "Action was called with parameters (uri => {$uriString}).\n".
            "Expected result is \"{$uriStringNormalized}\".\n".
            "Caught result is \"{$uriToStringConverted}\"."
        );
    }

    /**
     * Test "Uri::withPath" throws exception with invalid argument.
     *
     * @covers       Uri::withPath
     * @dataProvider dataProviderInvalidUri
     *
     * @param string $uriString full URI string
     */
    public function testThrowsException(string $uriString): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new UriFactory())->createUri($uriString);

        static::fail(
            "Action \"UriFactory->createUri\" threw no expected exception.\n".
            "Action was called with parameters (uri => {$uriString}).\n".
            "Expects \"InvalidArgumentException\" exception.\n".
            'Caught no exception.'
        );
    }

    /**
     * Data provider: full URI string with expected parsed parts.
     *
     * @return array data
     */
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

    /**
     * Data provider: full URI string invalid values.
     *
     * @return array data
     */
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
