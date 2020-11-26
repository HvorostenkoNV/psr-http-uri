<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters
};
use HNV\Http\UriTests\CombinationsProvider\CombinationsProviderInterface;
use HNV\Http\UriTests\ValuesProvider\Query as QueryValuesProvider;

use function strlen;
use function array_merge;
/** ***********************************************************************************************
 * URI full string different query combinations provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class QueryCombinations extends AbstractFullString implements CombinationsProviderInterface
{
    private static array $queryValidCombinations    = [];
    private static array $queryInvalidCombinations  = [];
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
        foreach (QueryValuesProvider::getValidValues() as $query => $queryNormalized) {
            self::$queryValidCombinations[$query] = strlen($queryNormalized) > 0
                ? UriGeneralDelimiters::QUERY_DELIMITER.$queryNormalized
                : '';
        }

        self::$queryInvalidCombinations = QueryValuesProvider::getInvalidValues();
    }
    /** **********************************************************************
     * Get full values data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getFullValues(): array
    {
        $result = [];

        foreach (self::$queryValidCombinations as $query => $queryNormalized) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => $query,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => $invalidQuery,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
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

        foreach (self::$queryValidCombinations as $query => $queryNormalized) {
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => $query,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => $query,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$pathNormalized.
                    $queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => $query,
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => $query,
                'fragment'  => '',
                'value'     =>
                    self::$pathNormalized.
                    $queryNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => $query,
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => $query,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => $query,
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $queryNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => $query,
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $queryNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => $invalidQuery,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => $invalidQuery,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => $invalidQuery,
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => $invalidQuery,
                'fragment'  => '',
                'value'     => UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => $invalidQuery,
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => $invalidQuery,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => $invalidQuery,
                'fragment'  => '',
                'value'     => UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => $invalidQuery,
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
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

        foreach (self::$queryValidCombinations as $query => $queryNormalized) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => $query,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    $queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => $query,
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => $query,
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => $query,
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    $queryNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => $invalidQuery,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => $invalidQuery,
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => '',
                'query'     => $invalidQuery,
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => 0,
                'path'      => self::$path,
                'query'     => $invalidQuery,
                'fragment'  => '',
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

        foreach (self::$queryValidCombinations as $query => $queryNormalized) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => $query,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => $query,
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    $queryNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => $invalidQuery,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => '',
                'query'     => $invalidQuery,
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority,
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

        foreach (self::$queryValidCombinations as $query => $queryNormalized) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => $query,
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $queryNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'scheme'    => self::$scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => self::$port,
                'path'      => self::$path,
                'query'     => $invalidQuery,
                'fragment'  => '',
                'value'     =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
            ];
        }

        return $result;
    }
}