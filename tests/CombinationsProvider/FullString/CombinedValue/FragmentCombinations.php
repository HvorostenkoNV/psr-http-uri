<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\CombinedValue;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters
};
use HNV\Http\UriTests\CombinationsProvider\{
    CombinationsProviderInterface,
    ValidValuesTrait
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
class FragmentCombinations implements CombinationsProviderInterface
{
    use ValidValuesTrait;

    private static array $fragmentValidCombinations     = [];
    private static array $fragmentInvalidCombinations   = [];
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        $result = [];

        self::initializeDefaultValues();
        self::initializeWorkableValues();

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
     * Initialize workable values.
     *
     * @return void
     ************************************************************************/
    private static function initializeWorkableValues()
    {
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
        $result = [];

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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
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
        $result = [];

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
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    self::$pathNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
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
                'value'     =>
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
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
                'value'     => UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
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
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
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
                'value'     => UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized,
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
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
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
        $result = [];

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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized,
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
        $result = [];

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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized,
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
        $result = [];

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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $fragmentNormalized,
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
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
            ];
        }

        return $result;
    }
}