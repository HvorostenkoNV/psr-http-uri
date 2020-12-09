<?php
declare(strict_types=1);

namespace HNV\Http\UriTests;

use Throwable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use HNV\Http\UriTests\CombinationsProvider\FullString\CombinedValue\{
    FullStringCombinations as FullStringCombinationsProvider
};
use HNV\Http\Uri\Uri;
/** ***********************************************************************************************
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with URI converting to string behavior.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UriToStringConvertingTest extends TestCase
{
    /** **********************************************************************
     * Test URI object converts to string in expected way.
     *
     * @covers          Uri::__toString
     * @dataProvider    dataProviderUriByParts
     *
     * @param           string  $scheme                     Scheme.
     * @param           string  $login                      Login.
     * @param           string  $password                   Password.
     * @param           string  $host                       Host.
     * @param           int     $port                       Port.
     * @param           string  $path                       Path.
     * @param           string  $query                      Query.
     * @param           string  $fragment                   Fragment.
     * @param           string  $uriExpected                Expected normalized URI string.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testToStringConverting(
        string  $scheme,
        string  $login,
        string  $password,
        string  $host,
        int     $port,
        string  $path,
        string  $query,
        string  $fragment,
        string  $uriExpected
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

        $uri        = $uri->withFragment($fragment);
        $uriCaught  = (string) $uri;

        self::assertEquals(
            $uriExpected,
            $uriCaught,
            "Action \"Uri->withScheme->withUserInfo->withHost->withPort".
            "->withPath->withQuery->withFragment\" returned unexpected result.\n".
            "Action was called with parameters (scheme => $scheme, login => $login,".
            " password => $password, host => $host, port => $port, path => $path,".
            " query => $query, fragment => $fragment).\n".
            "Expected result is \"$uriExpected\".\n".
            "Caught result is \"$uriCaught\"."
        );
    }
    /** **********************************************************************
     * Data provider: URI by parts.
     *
     * @return  array                               Data.
     ************************************************************************/
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