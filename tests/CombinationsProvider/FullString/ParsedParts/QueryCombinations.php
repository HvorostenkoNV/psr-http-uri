<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\ParsedParts;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters,
};
use HNV\Http\UriTests\CombinationsProvider\{
    CombinationsProviderInterface,
    AbstractCombinationsProvider,
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
class QueryCombinations extends AbstractCombinationsProvider implements CombinationsProviderInterface
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
     * @inheritDoc
     ************************************************************************/
    protected static function initializeDefaultValues(): void
    {
        parent::initializeDefaultValues();

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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER;
        $result             = [];

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value'             => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathDelimiter.self::$path.
                    $queryDelimiter.$combination['value'].$fragmentDelimiter.self::$fragment,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => $pathDelimiter.self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.$combination['asPartOfFullString'].
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value'             => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathDelimiter.self::$path.
                    $queryDelimiter.$invalidQuery.$fragmentDelimiter.self::$fragment,
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
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER;
        $result             = [];

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value'             => $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$combination['value'].
                    $fragmentDelimiter.self::$fragment,
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
                'value'             => $pathDelimiter.self::$path.
                    $queryDelimiter.$combination['value'].$fragmentDelimiter.self::$fragment,
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => $pathDelimiter.self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   => $pathDelimiter.self::$pathNormalized.
                    $combination['asPartOfFullString'].
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'             => $queryDelimiter.$combination['value'].
                    $fragmentDelimiter.self::$fragment,
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
                'value'             => $pathDelimiter.self::$path.
                    $queryDelimiter.$combination['value'],
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => $pathDelimiter.self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => '',
                'valueNormalized'   => $pathDelimiter.self::$pathNormalized.
                    $combination['asPartOfFullString'],
            ];
            $result[] = [
                'value'             => $queryDelimiter.$combination['value'],
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
                'value'             => $authorityDelimiter.self::$authority.
                    $queryDelimiter.$combination['value'].$fragmentDelimiter.self::$fragment,
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
                'value'             => $authorityDelimiter.self::$authority.
                    $queryDelimiter.$combination['value'],
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
                'value'             => $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$combination['value'],
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
                'value'             => $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery.
                    $fragmentDelimiter.self::$fragment,
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
                'value'             => $pathDelimiter.self::$path.
                    $queryDelimiter.$invalidQuery.$fragmentDelimiter.self::$fragment,
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
                'value'             => $queryDelimiter.$invalidQuery.
                    $fragmentDelimiter.self::$fragment,
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
                'value'             => $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery,
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
                'value'             => $queryDelimiter.$invalidQuery,
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
                'value'             => $authorityDelimiter.self::$authority.
                    $queryDelimiter.$invalidQuery.$fragmentDelimiter.self::$fragment,
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
                'value'             => $authorityDelimiter.self::$authority.
                    $queryDelimiter.$invalidQuery,
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
                'value'             => $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery,
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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER;
        $result             = [];

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value'             => self::$scheme.$schemeDelimiter.
                    $pathDelimiter.self::$path.$queryDelimiter.$combination['value'].
                    $fragmentDelimiter.self::$fragment,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => $pathDelimiter.self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   => self::$schemeNormalized.$schemeDelimiter.
                    $pathDelimiter.self::$pathNormalized.$combination['asPartOfFullString'].
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'             => self::$scheme.$schemeDelimiter.
                    $queryDelimiter.$combination['value'].$fragmentDelimiter.self::$fragment,
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
                'value'             => self::$scheme.$schemeDelimiter.
                    $queryDelimiter.$combination['value'],
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
                'value'             => self::$scheme.$schemeDelimiter.
                    $pathDelimiter.self::$path.$queryDelimiter.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              => $pathDelimiter.self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => '',
                'valueNormalized'   => self::$schemeNormalized.$schemeDelimiter.
                    $pathDelimiter.self::$pathNormalized.$combination['asPartOfFullString'],
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value'             => self::$scheme.$schemeDelimiter.
                    $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery.
                    $fragmentDelimiter.self::$fragment,
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
                'value'             => self::$scheme.$schemeDelimiter.
                    $queryDelimiter.$invalidQuery.$fragmentDelimiter.self::$fragment,
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
                'value'             => self::$scheme.$schemeDelimiter.
                    $queryDelimiter.$invalidQuery,
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
                'value'             => self::$scheme.$schemeDelimiter.
                    $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery,
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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER;
        $result             = [];

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value'             => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.$combination['value'].
                    $fragmentDelimiter.self::$fragment,
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => '',
                'query'             => $combination['valueNormalized'],
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $combination['asPartOfFullString'].
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'             => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => '',
                'query'             => $combination['valueNormalized'],
                'fragment'          => '',
                'valueNormalized'   => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $combination['asPartOfFullString'],
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value'             => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $queryDelimiter.$invalidQuery.$fragmentDelimiter.self::$fragment,
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
                'value'             => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.$invalidQuery,
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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $result             = [];

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value'             => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => $pathDelimiter.self::$pathNormalized,
                'query'             => $combination['valueNormalized'],
                'fragment'          => '',
                'valueNormalized'   => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.$combination['asPartOfFullString'],
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value'             => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery,
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
