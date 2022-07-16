<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\ParsedParts;

use HNV\Http\Uri\Collection\{
    AuthorityRules,
    FragmentRules,
    PathRules,
    QueryRules,
    SchemeRules,
    UriDelimiters,
};
use HNV\Http\UriTests\CombinationsProvider\{
    AbstractCombinationsProvider,
    CombinationsProviderInterface,
};
use HNV\Http\UriTests\ValuesProvider\Query as QueryValuesProvider;

use function array_merge;
use function str_contains;
use function strlen;

/**
 * URI full string with it`s parsed parts provider (query combinations).
 */
class QueryCombinations extends AbstractCombinationsProvider implements CombinationsProviderInterface
{
    private static array $queryValidCombinations   = [];
    private static array $queryInvalidCombinations = [];

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    protected static function initializeDefaultValues(): void
    {
        parent::initializeDefaultValues();

        foreach (QueryValuesProvider::getValidValues() as $query => $queryNormalized) {
            foreach (UriDelimiters::generalDelimiters() as $case) {
                if (str_contains($query, $case->value)) {
                    continue 2;
                }
            }

            self::$queryValidCombinations[] = [
                'value'              => $query,
                'valueNormalized'    => $queryNormalized,
                'asPartOfFullString' => strlen($queryNormalized) > 0
                    ? QueryRules::URI_DELIMITER->value.$queryNormalized
                    : '',
            ];
        }

        self::$queryInvalidCombinations = QueryValuesProvider::getInvalidValues();
    }

    /**
     * Get full values data set.
     */
    private static function getFullValues(): array
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;
        $result             = [];

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathDelimiter.self::$path.
                    $queryDelimiter.$combination['value'].$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => $combination['valueNormalized'],
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.$combination['asPartOfFullString'].
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathDelimiter.self::$path.
                    $queryDelimiter.$invalidQuery.$fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
        }

        return $result;
    }

    /**
     * Get values without scheme data set.
     */
    private static function getValuesWithoutScheme(): array
    {
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;
        $result             = [];

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$combination['value'].
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $pathDelimiter.self::$path.
                    $queryDelimiter.$combination['value'].$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => $combination['valueNormalized'],
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $pathDelimiter.self::$pathNormalized.
                    $combination['asPartOfFullString'].
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value' => $queryDelimiter.$combination['value'].
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $pathDelimiter.self::$path.
                    $queryDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => $combination['valueNormalized'],
                'fragment'        => '',
                'valueNormalized' => $pathDelimiter.self::$pathNormalized.
                    $combination['asPartOfFullString'],
            ];
            $result[] = [
                'value'           => $queryDelimiter.$combination['value'],
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.
                    $queryDelimiter.$combination['value'].$fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.
                    $queryDelimiter.$combination['value'],
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$combination['value'],
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery.
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $pathDelimiter.self::$path.
                    $queryDelimiter.$invalidQuery.$fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $queryDelimiter.$invalidQuery.
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => $queryDelimiter.$invalidQuery,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.
                    $queryDelimiter.$invalidQuery.$fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.
                    $queryDelimiter.$invalidQuery,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
        }

        return $result;
    }

    /**
     * Get values without authority data set.
     */
    private static function getValuesWithoutAuthority(): array
    {
        $schemeDelimiter   = SchemeRules::URI_DELIMITER->value;
        $pathDelimiter     = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter    = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;
        $result            = [];

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $pathDelimiter.self::$path.$queryDelimiter.$combination['value'].
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => $combination['valueNormalized'],
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $pathDelimiter.self::$pathNormalized.$combination['asPartOfFullString'].
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $queryDelimiter.$combination['value'].$fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $queryDelimiter.$combination['value'],
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $pathDelimiter.self::$path.$queryDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => $combination['valueNormalized'],
                'fragment'        => '',
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $pathDelimiter.self::$pathNormalized.$combination['asPartOfFullString'],
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery.
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $queryDelimiter.$invalidQuery.$fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $queryDelimiter.$invalidQuery,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
        }

        return $result;
    }

    /**
     * Get values without path data set.
     */
    private static function getValuesWithoutPath(): array
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;
        $result             = [];

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.$combination['value'].
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => '',
                'query'           => $combination['valueNormalized'],
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $combination['asPartOfFullString'].
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => '',
                'query'           => $combination['valueNormalized'],
                'fragment'        => '',
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $combination['asPartOfFullString'],
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $queryDelimiter.$invalidQuery.$fragmentDelimiter.self::$fragment,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.$invalidQuery,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
        }

        return $result;
    }

    /**
     * Get values without fragment data set.
     */
    private static function getValuesWithoutFragment(): array
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $result             = [];

        foreach (self::$queryValidCombinations as $combination) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => $combination['valueNormalized'],
                'fragment'        => '',
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.$combination['asPartOfFullString'],
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.$invalidQuery,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => '',
            ];
        }

        return $result;
    }
}
