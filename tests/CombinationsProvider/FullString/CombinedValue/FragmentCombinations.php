<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\CombinedValue;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters,
};
use HNV\Http\UriTests\CombinationsProvider\{
    CombinationsProviderInterface,
    AbstractCombinationsProvider,
};
use HNV\Http\UriTests\ValuesProvider\Fragment as FragmentValuesProvider;

use function strlen;
use function array_merge;
/** ***********************************************************************************************
 * URI full string different combinations provider (fragment combinations).
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class FragmentCombinations extends AbstractCombinationsProvider implements CombinationsProviderInterface
{
    private static array $fragmentValidCombinations     = [];
    private static array $fragmentInvalidCombinations   = [];
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        $result = [];

        self::initializeDefaultValues();

        foreach ([
            self::getFullValues(),
            self::getValuesWithoutScheme(),
            self::getValuesWithoutAuthority(),
            self::getValuesWithoutPath(),
            self::getValuesWithoutQuery(),
        ] as $data) {
            $result = array_merge($result, $data);
        }

        return $result;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    protected static function initializeDefaultValues(): void
    {
        parent::initializeDefaultValues();

        foreach (FragmentValuesProvider::getValidValues() as $fragment => $fragmentNormalized) {
            self::$fragmentValidCombinations[$fragment] = strlen($fragmentNormalized) > 0
                ? UriGeneralDelimiters::FRAGMENT_DELIMITER.$fragmentNormalized
                : '';
        }

        self::$fragmentInvalidCombinations = FragmentValuesProvider::getInvalidValues();
    }
    /** **********************************************************************
     * Get full values data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getFullValues(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $result             = [];

        foreach (self::$fragmentValidCombinations as $fragment => $fragmentNormalized) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => (string) $fragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.$fragmentNormalized,
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => $invalidFragment,
                'value'     => '',
            ];
        }

        return $result;
    }
    /** **********************************************************************
     * Get values without scheme data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getValuesWithoutScheme(): array
    {
        $pathDelimiter  = UriSubDelimiters::PATH_PARTS_SEPARATOR;
        $queryDelimiter = UriGeneralDelimiters::QUERY_DELIMITER;
        $result         = [];

        foreach (self::$fragmentValidCombinations as $fragment => $fragmentNormalized) {
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => (string) $fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => (string) $fragment,
                'value'     => self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => (string) $fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => (string) $fragment,
                'value'     => self::$pathNormalized.$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => '',
                'fragment'  => (string) $fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => (string) $fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => '',
                'fragment'  => (string) $fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => (string) $fragment,
                'value'     => '',
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => $invalidFragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => $invalidFragment,
                'value'     => self::$pathNormalized.$queryDelimiter.self::$queryNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => $invalidFragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => $invalidFragment,
                'value'     => $pathDelimiter.self::$pathNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => '',
                'fragment'  => $invalidFragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => $invalidFragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => '',
                'fragment'  => $invalidFragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => $invalidFragment,
                'value'     => '',
            ];
        }

        return $result;
    }
    /** **********************************************************************
     * Get values without authority data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getValuesWithoutAuthority(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $result             = [];

        foreach (self::$fragmentValidCombinations as $fragment => $fragmentNormalized) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => (string) $fragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.
                    self::$pathNormalized.$queryDelimiter.self::$queryNormalized.
                    $fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => (string) $fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => '',
                'fragment'  => (string) $fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => (string) $fragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.
                    self::$pathNormalized.$fragmentNormalized,
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => $invalidFragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.
                    self::$pathNormalized.$queryDelimiter.self::$queryNormalized,
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => $invalidFragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => '',
                'fragment'  => $invalidFragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => $invalidFragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.self::$pathNormalized,
            ];
        }

        return $result;
    }
    /** **********************************************************************
     * Get values without path data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getValuesWithoutPath(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $result             = [];

        foreach (self::$fragmentValidCombinations as $fragment => $fragmentNormalized) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => (string) $fragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $queryDelimiter.self::$queryNormalized.$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => '',
                'fragment'  => (string) $fragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.$fragmentNormalized,
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => $invalidFragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $queryDelimiter.self::$queryNormalized,
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => '',
                'fragment'  => $invalidFragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized,
            ];
        }

        return $result;
    }
    /** **********************************************************************
     * Get values without query data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getValuesWithoutQuery(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR;
        $result             = [];

        foreach (self::$fragmentValidCombinations as $fragment => $fragmentNormalized) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => (string) $fragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.$fragmentNormalized,
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => $invalidFragment,
                'value'     => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized,
            ];
        }

        return $result;
    }
}
