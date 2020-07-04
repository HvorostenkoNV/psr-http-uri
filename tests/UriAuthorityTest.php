<?php
declare(strict_types=1);

namespace HNV\Http\UriTests;

use Throwable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use HNV\Http\UriTests\CombinationsProvider\Authority as AuthorityCombinationsProvider;
use HNV\Http\Uri\Uri;
/** ***********************************************************************************************
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with authority values.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UriAuthorityTest extends TestCase
{
    /** **********************************************************************
     * Test "Uri::getAuthority" provides valid normalized value.
     *
     * @covers          Uri::getAuthority
     * @dataProvider    dataProviderAuthorityByParts
     *
     * @param           string  $scheme                     Scheme.
     * @param           string  $login                      Login.
     * @param           string  $password                   Password.
     * @param           string  $host                       Host.
     * @param           int     $port                       Port.
     * @param           string  $valueNormalized            Normalized value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testGetValue(
        string  $scheme,
        string  $login,
        string  $password,
        string  $host,
        int     $port,
        string  $valueNormalized
    ): void {
        $uri = (new Uri())
            ->withScheme($scheme)
            ->withUserInfo($login, $password);

        try {
            $uri = $uri->withHost($host);
        } catch (InvalidArgumentException $exception) {

        }

        try {
            $uri = $uri->withPort($port);
        } catch (InvalidArgumentException $exception) {

        }

        $valueCaught = $uri->getAuthority();

        self::assertEquals(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withScheme->withUserInfo->withHost->withPort->getAuthority\"".
            " returned unexpected result.\n".
            "Action was called with parameters (scheme => $scheme, login => $login,".
            " password => $password, host => $host, port => $port).\n".
            "Expected result is \"$valueNormalized\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::getAuthority" provides expects value from empty object.
     *
     * @covers  Uri::getAuthority
     *
     * @return  void
     * @throws  Throwable
     ************************************************************************/
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getAuthority();

        self::assertEquals(
            '',
            $valueCaught,
            "Action \"Uri->getAuthority\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Data provider: authority by parts.
     *
     * @return  array                                       Data.
     ************************************************************************/
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