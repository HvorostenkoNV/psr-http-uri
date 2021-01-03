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
use HNV\Http\UriTests\ValuesProvider\Scheme as SchemeValuesProvider;

use function array_merge;
/** ***********************************************************************************************
 * URI full string with it`s parsed parts provider (scheme combinations).
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class SchemeCombinations implements CombinationsProviderInterface
{
    use ValidValuesTrait;
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        $result = [];

        self::initializeDefaultValues();

        foreach ([
            self::getFullValues(),
            self::getValuesWithoutAuthority(),
            self::getValuesWithoutPath(),
            self::getValuesWithoutQuery(),
            self::getValuesWithoutFragment(),
        ] as $data) {
            $result = array_merge($result, $data);
        }

        return $result;
    }
    /** **********************************************************************
     * Get full values data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getFullValues(): array
    {
        $result = [];

        foreach (SchemeValuesProvider::getValidValues() as $scheme => $schemeNormalized) {
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => self::$pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
        }
        foreach (SchemeValuesProvider::getInvalidValues() as $invalidScheme) {
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
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
     * Get values without authority data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getValuesWithoutAuthority(): array
    {
        $result = [];

        foreach (SchemeValuesProvider::getValidValues() as $scheme => $schemeNormalized) {
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => self::$pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
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
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
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
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
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
            $result[]   = [
                'value'             => $scheme.UriGeneralDelimiters::SCHEME_DELIMITER,
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
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => self::$pathNormalized,
                'query'             => '',
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$path,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => self::$pathNormalized,
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized,
            ];
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => self::$pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => '',
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (SchemeValuesProvider::getInvalidValues() as $invalidScheme) {
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$path.
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
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
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
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
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
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
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
            $result[]   = [
                'value'             => $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER,
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
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$path.
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
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$path,
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
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$path.
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
    /** **********************************************************************
     * Get values without path data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getValuesWithoutPath(): array
    {
        $result = [];

        foreach (SchemeValuesProvider::getValidValues() as $scheme => $schemeNormalized) {
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => '',
                'query'             => self::$queryNormalized,
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => '',
                'query'             => '',
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => '',
                'query'             => self::$queryNormalized,
                'fragment'          => '',
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized,
            ];
        }
        foreach (SchemeValuesProvider::getInvalidValues() as $invalidScheme) {
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
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
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
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
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
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
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority,
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
     * Get values without query data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getValuesWithoutQuery(): array
    {
        $result = [];

        foreach (SchemeValuesProvider::getValidValues() as $scheme => $schemeNormalized) {
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => self::$pathNormalized,
                'query'             => '',
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => self::$pathNormalized,
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
            ];
        }
        foreach (SchemeValuesProvider::getInvalidValues() as $invalidScheme) {
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
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
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path,
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

        foreach (SchemeValuesProvider::getValidValues() as $scheme => $schemeNormalized) {
            $result[]   = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'           => true,
                'scheme'            => $schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => self::$pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => '',
                'valueNormalized'   =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (SchemeValuesProvider::getInvalidValues() as $invalidScheme) {
            $result[]   = [
                'value'             =>
                    $invalidScheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
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