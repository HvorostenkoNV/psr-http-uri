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
use HNV\Http\UriTests\ValuesProvider\Path as PathValuesProvider;

use function str_starts_with;
use function ltrim;
use function array_merge;
/** ***********************************************************************************************
 * URI full string different combinations provider (path combinations).
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class PathCombinations implements CombinationsProviderInterface
{
    use ValidValuesTrait;

    private static array $pathValidCombinations                 = [];
    private static array $pathInvalidCombinations               = [];
    private static array $pathValidCombinationsWithoutAuthority = [];
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
            self::getValuesWithoutQuery(),
            self::getValuesWithoutFragment(),
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
        $pathSeparator = UriSubDelimiters::PATH_PARTS_SEPARATOR;

        foreach (PathValuesProvider::getValidValues() as $path => $pathNormalized) {
            $startsWithSeparator = str_starts_with($pathNormalized, $pathSeparator);

            self::$pathValidCombinations[$path]                 = $startsWithSeparator
                ? $pathNormalized
                : $pathSeparator.$pathNormalized;
            self::$pathValidCombinationsWithoutAuthority[$path] = $startsWithSeparator
                ? $pathSeparator.ltrim($pathNormalized, $pathSeparator)
                : $pathNormalized;
        }

        self::$pathInvalidCombinations = PathValuesProvider::getInvalidValues();
    }
    /** **********************************************************************
     * Get full values data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getFullValues(): array
    {
        $result = [];

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $invalidPath,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
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

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[]   = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $path,
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $invalidPath,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $invalidPath,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $invalidPath,
                'query'     => self::$query,
                'fragment'  => '',
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
                'path'      => $invalidPath,
                'query'     => '',
                'fragment'  => '',
                'value'     => UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized,
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

        foreach (self::$pathValidCombinationsWithoutAuthority as $path => $pathNormalized) {
            $result[]   = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    $pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $path,
                'query'     => '',
                'fragment'  => '',
                'value'     => $pathNormalized,
            ];
            $result[]   = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    $pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
            $result[]   = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $path,
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    $pathNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $invalidPath,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $invalidPath,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $invalidPath,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $invalidPath,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $invalidPath,
                'query'     => '',
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $invalidPath,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $invalidPath,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => $invalidPath,
                'query'     => '',
                'fragment'  => '',
                'value'     => '',
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

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[]   = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $path,
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $invalidPath,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $invalidPath,
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized,
            ];
        }

        return $result;
    }
    /** **********************************************************************
     * Get values without fragment data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getValuesWithoutFragment(): array
    {
        $result = [];

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[]   = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => $invalidPath,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }

        return $result;
    }
}