<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\ParsedParts;

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
use function str_contains;
use function ltrim;
use function array_keys;
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
            foreach (UriGeneralDelimiters::get() as $char) {
                if (str_contains($path, $char)) {
                    continue 2;
                }
            }

            $pathWithSeparatorPrefix            = str_starts_with($path, $pathSeparator)
                ? $path
                : $pathSeparator.$path;
            $pathNormalizedWithSeparatorPrefix  = str_starts_with($pathNormalized, $pathSeparator)
                ? $pathNormalized
                : $pathSeparator.$pathNormalized;
            $pathNormalizedWithMinimizedPrefix  = str_starts_with($pathNormalized, $pathSeparator)
                ? $pathSeparator.ltrim($pathNormalized, $pathSeparator)
                : $pathNormalized;

            self::$pathValidCombinations[$pathWithSeparatorPrefix]  = $pathNormalizedWithSeparatorPrefix;
            self::$pathValidCombinationsWithoutAuthority[$path]     = $pathNormalizedWithMinimizedPrefix;
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
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => $pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
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

        foreach (array_keys(self::$pathValidCombinations) as $path) {
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => '',
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => '',
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => '',
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => '',
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => '',
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => '',
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => '',
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => '',
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
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
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    $path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => $pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
//            $result[] = [
//                'value'             =>
//                    $path.
//                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
//                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
//                'isValid'           => true,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => $pathNormalized,
//                'query'             => self::$queryNormalized,
//                'fragment'          => self::$fragmentNormalized,
//                'valueNormalized'   =>
//                    $pathNormalized.
//                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
//                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
//            ];
//            $result[] = [
//                'value'             =>
//                    $path.
//                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
//                'isValid'           => true,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => $pathNormalized,
//                'query'             => '',
//                'fragment'          => self::$fragmentNormalized,
//                'valueNormalized'   =>
//                    $pathNormalized.
//                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
//            ];
//            $result[] = [
//                'value'             =>
//                    $path.
//                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
//                'isValid'           => true,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => $pathNormalized,
//                'query'             => self::$queryNormalized,
//                'fragment'          => '',
//                'valueNormalized'   =>
//                    $pathNormalized.
//                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
//            ];
//            $result[] = [
//                'value'             => $path,
//                'isValid'           => true,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => $pathNormalized,
//                'query'             => '',
//                'fragment'          => '',
//                'valueNormalized'   => $pathNormalized,
//            ];
//            $result[] = [
//                'value'             =>
//                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
//                    $path.
//                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
//                'isValid'           => true,
//                'scheme'            => self::$schemeNormalized,
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => $pathNormalized,
//                'query'             => '',
//                'fragment'          => self::$fragmentNormalized,
//                'valueNormalized'   =>
//                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
//                    $pathNormalized.
//                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
//            ];
//            $result[] = [
//                'value'             =>
//                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
//                    $path.
//                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
//                'isValid'           => true,
//                'scheme'            => self::$schemeNormalized,
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => $pathNormalized,
//                'query'             => self::$queryNormalized,
//                'fragment'          => '',
//                'valueNormalized'   =>
//                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
//                    $pathNormalized.
//                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
//            ];
//            $result[] = [
//                'value'             =>
//                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
//                    $path,
//                'isValid'           => true,
//                'scheme'            => self::$schemeNormalized,
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => $pathNormalized,
//                'query'             => '',
//                'fragment'          => '',
//                'valueNormalized'   =>
//                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
//                    $pathNormalized,
//            ];
        }
//        foreach (self::$pathInvalidCombinations as $invalidPath) {
//            $result[] = [
//                'value'             =>
//                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
//                    $invalidPath.
//                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
//                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
//                'isValid'           => false,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => '',
//                'query'             => '',
//                'fragment'          => '',
//                'valueNormalized'   => '',
//            ];
//            $result[] = [
//                'value'             =>
//                    $invalidPath.
//                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
//                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
//                'isValid'           => false,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => '',
//                'query'             => '',
//                'fragment'          => '',
//                'valueNormalized'   => '',
//            ];
//            $result[] = [
//                'value'             =>
//                    $invalidPath.
//                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
//                'isValid'           => false,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => '',
//                'query'             => '',
//                'fragment'          => '',
//                'valueNormalized'   => '',
//            ];
//            $result[] = [
//                'value'             =>
//                    $invalidPath.
//                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
//                'isValid'           => false,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => '',
//                'query'             => '',
//                'fragment'          => '',
//                'valueNormalized'   => '',
//            ];
//            $result[] = [
//                'value'             => $invalidPath,
//                'isValid'           => false,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => '',
//                'query'             => '',
//                'fragment'          => '',
//                'valueNormalized'   => '',
//            ];
//            $result[] = [
//                'value'             =>
//                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
//                    $invalidPath.
//                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
//                'isValid'           => false,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => '',
//                'query'             => '',
//                'fragment'          => '',
//                'valueNormalized'   => '',
//            ];
//            $result[] = [
//                'value'             =>
//                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
//                    $invalidPath.
//                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
//                'isValid'           => false,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => '',
//                'query'             => '',
//                'fragment'          => '',
//                'valueNormalized'   => '',
//            ];
//            $result[] = [
//                'value'             =>
//                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
//                    $invalidPath,
//                'isValid'           => false,
//                'scheme'            => '',
//                'userInfo'          => '',
//                'host'              => '',
//                'port'              => 0,
//                'authority'         => '',
//                'path'              => '',
//                'query'             => '',
//                'fragment'          => '',
//                'valueNormalized'   => '',
//            ];
//        }

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
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => $pathNormalized,
                'query'             => '',
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => $pathNormalized,
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
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
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => $pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => '',
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => '',
            ];
        }

        return $result;
    }
}