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
//            self::getValuesWithoutScheme(),
//            self::getValuesWithoutAuthority(),
//            self::getValuesWithoutQuery(),
//            self::getValuesWithoutFragment(),
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

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[] = [
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'   => true,
                'scheme'    => '',
                'userInfo'  => self::$userInfoNormalized,
                'host'      => self::$hostNormalized,
                'port'      => self::$portNormalized,
                'authority' => self::$authorityNormalized,
                'path'      => $pathNormalized,
                'query'     => self::$queryNormalized,
                'fragment'  => self::$fragmentNormalized,
            ];
            $result[] = [
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'   => true,
                'scheme'    => '',
                'userInfo'  => self::$userInfoNormalized,
                'host'      => self::$hostNormalized,
                'port'      => self::$portNormalized,
                'authority' => self::$authorityNormalized,
                'path'      => $pathNormalized,
                'query'     => '',
                'fragment'  => self::$fragmentNormalized,
            ];
            $result[] = [
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'   => true,
                'scheme'    => '',
                'userInfo'  => self::$userInfoNormalized,
                'host'      => self::$hostNormalized,
                'port'      => self::$portNormalized,
                'authority' => self::$authorityNormalized,
                'path'      => $pathNormalized,
                'query'     => self::$queryNormalized,
                'fragment'  => '',
            ];
            $result[] = [
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $path,
                'isValid'   => true,
                'scheme'    => '',
                'userInfo'  => self::$userInfoNormalized,
                'host'      => self::$hostNormalized,
                'port'      => self::$portNormalized,
                'authority' => self::$authorityNormalized,
                'path'      => $pathNormalized,
                'query'     => '',
                'fragment'  => '',
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'   => false,
                'scheme'    => '',
                'userInfo'  => '',
                'host'      => '',
                'port'      => 0,
                'authority' => '',
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
            ];
            $result[] = [
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'   => false,
                'scheme'    => '',
                'userInfo'  => '',
                'host'      => '',
                'port'      => 0,
                'authority' => '',
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
            ];
            $result[] = [
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'   => false,
                'scheme'    => '',
                'userInfo'  => '',
                'host'      => '',
                'port'      => 0,
                'authority' => '',
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
            ];
            $result[] = [
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $invalidPath,
                'isValid'   => false,
                'scheme'    => '',
                'userInfo'  => '',
                'host'      => '',
                'port'      => 0,
                'authority' => '',
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
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
        //TODO
        $result = [];

        foreach (self::$pathValidCombinationsWithoutAuthority as $path => $pathNormalized) {
            $result[] = [
                'value'     =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    $path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'   => true,
                'scheme'    => self::$schemeNormalized,
                'userInfo'  => '',
                'host'      => '',
                'port'      => 0,
                'authority' => '',
                'path'      => $pathNormalized,
                'query'     => self::$queryNormalized,
                'fragment'  => self::$fragmentNormalized,
            ];
            $result[] = [
                'value'     =>
                    $path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'   => true,
                'scheme'    => '',
                'userInfo'  => '',
                'host'      => '',
                'port'      => 0,
                'authority' => '',
                'path'      => $pathNormalized,
                'query'     => self::$queryNormalized,
                'fragment'  => self::$fragmentNormalized,
            ];






            $result[] = [
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
            $result[] = [
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
            $result[] = [
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
            $result[] = [
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
            $result[] = [
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
            $result[] = [
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
            $result[] = [
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
        //TODO
        $result = [];

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[] = [
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
            $result[] = [
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
        //TODO
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