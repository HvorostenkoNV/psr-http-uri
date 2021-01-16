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
use HNV\Http\UriTests\ValuesProvider\Query as QueryValuesProvider;

use function strlen;
use function str_contains;
use function array_merge;
/** ***********************************************************************************************
 * URI full string with it`s parsed parts provider (query combinations).
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class QueryCombinations implements CombinationsProviderInterface
{
    use ValidValuesTrait;

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
            foreach (UriGeneralDelimiters::get() as $char) {
                if (str_contains($query, $char)) {
                    continue 2;
                }
            }

            self::$queryValidCombinations[] = [
                'value'                 => $query,
                'valueNormalized'       => $queryNormalized,
                'asPartOfFullString'    => strlen($queryNormalized) > 0
                    ? UriGeneralDelimiters::QUERY_DELIMITER.$queryNormalized
                    : '',
            ];
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

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $combination['asPartOfFullString'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery.
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

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'].
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
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $combination['asPartOfFullString'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'].
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
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => '',
                'valueNormalized'   =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $combination['asPartOfFullString'],
            ];
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'],
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'].
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'],
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'],
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
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery.
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
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery.
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
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery.
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
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery,
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
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery,
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery.
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery,
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery,
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

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $combination['asPartOfFullString'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'].
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
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'],
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
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => '',
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $combination['asPartOfFullString'],
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery.
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
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery.
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
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery,
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
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery,
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

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => '',
                'query'             => $combination['valueNormalized'],
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $combination['asPartOfFullString'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => '',
                'query'             => $combination['valueNormalized'],
                'fragment'          => '',
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $combination['asPartOfFullString'],
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery.
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
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery,
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

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => '',
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $combination['asPartOfFullString'],
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.$invalidQuery,
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