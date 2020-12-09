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
use HNV\Http\UriTests\ValuesProvider\Scheme as SchemeValuesProvider;

use function array_merge;
/** ***********************************************************************************************
 * URI full string different scheme combinations provider.
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
            $result[] = [
                'scheme'    => $scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
        }
        foreach (SchemeValuesProvider::getInvalidValues() as $invalidScheme) {
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
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
            $result[] = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized,
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (SchemeValuesProvider::getInvalidValues() as $invalidScheme) {
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => '',
                'value'     => self::$pathNormalized,
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
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
            $result[] = [
                'scheme'    => $scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized,
            ];
        }
        foreach (SchemeValuesProvider::getInvalidValues() as $invalidScheme) {
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
                'value'     => UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized,
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
            $result[] = [
                'scheme'    => $scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
            ];
        }
        foreach (SchemeValuesProvider::getInvalidValues() as $invalidScheme) {
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
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
            $result[] = [
                'scheme'    => $scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    $schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (SchemeValuesProvider::getInvalidValues() as $invalidScheme) {
            $result[] = [
                'scheme'    => $invalidScheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }

        return $result;
    }
}