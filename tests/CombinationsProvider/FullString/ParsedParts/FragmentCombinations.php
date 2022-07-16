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
use HNV\Http\UriTests\ValuesProvider\Fragment as FragmentValuesProvider;

use function array_merge;
use function str_contains;
use function strlen;

/**
 * URI full string with it`s parsed parts provider (fragment combinations).
 */
class FragmentCombinations extends AbstractCombinationsProvider implements CombinationsProviderInterface
{
    private static array $fragmentValidCombinations   = [];
    private static array $fragmentInvalidCombinations = [];

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
            self::getValuesWithoutQuery(),
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

        foreach (FragmentValuesProvider::getValidValues() as $fragment => $fragmentNormalized) {
            $fragmentString = (string) $fragment;

            foreach (UriDelimiters::generalDelimiters() as $case) {
                if (str_contains($fragmentString, $case->value)) {
                    continue 2;
                }
            }

            self::$fragmentValidCombinations[] = [
                'value'              => $fragmentString,
                'valueNormalized'    => $fragmentNormalized,
                'asPartOfFullString' => strlen($fragmentNormalized) > 0
                    ? FragmentRules::URI_DELIMITER->value.$fragmentNormalized
                    : '',
            ];
        }

        self::$fragmentInvalidCombinations = FragmentValuesProvider::getInvalidValues();
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

        foreach (self::$fragmentValidCombinations as $combination) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathDelimiter.self::$path.
                    $queryDelimiter.self::$query.$fragmentDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => $combination['valueNormalized'],
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.$combination['asPartOfFullString'],
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathDelimiter.self::$path.
                    $queryDelimiter.self::$query.$fragmentDelimiter.$invalidFragment,
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

        foreach (self::$fragmentValidCombinations as $combination) {
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.self::$query.
                    $fragmentDelimiter.$combination['value'],
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
                    $queryDelimiter.self::$query.$fragmentDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => $combination['valueNormalized'],
                'valueNormalized' => $pathDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.$combination['asPartOfFullString'],
            ];
            $result[] = [
                'value' => $queryDelimiter.self::$query.
                    $fragmentDelimiter.$combination['value'],
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
                    $fragmentDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => '',
                'fragment'        => $combination['valueNormalized'],
                'valueNormalized' => $pathDelimiter.self::$pathNormalized.
                    $combination['asPartOfFullString'],
            ];
            $result[] = [
                'value'           => $fragmentDelimiter.$combination['value'],
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
                    $queryDelimiter.self::$query.$fragmentDelimiter.$combination['value'],
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
                    $fragmentDelimiter.$combination['value'],
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
                    $pathDelimiter.self::$path.$fragmentDelimiter.$combination['value'],
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
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$queryDelimiter.self::$query.
                    $fragmentDelimiter.$invalidFragment,
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
                    $queryDelimiter.self::$query.$fragmentDelimiter.$invalidFragment,
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
                'value' => $queryDelimiter.self::$query.
                    $fragmentDelimiter.$invalidFragment,
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
                    $fragmentDelimiter.$invalidFragment,
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
                'value'           => $fragmentDelimiter.$invalidFragment,
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
                    $queryDelimiter.self::$query.$fragmentDelimiter.$invalidFragment,
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
                    $fragmentDelimiter.$invalidFragment,
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
                    $pathDelimiter.self::$path.$fragmentDelimiter.$invalidFragment,
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

        foreach (self::$fragmentValidCombinations as $combination) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $pathDelimiter.self::$path.$queryDelimiter.self::$query.
                    $fragmentDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => $combination['valueNormalized'],
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $pathDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.$combination['asPartOfFullString'],
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $queryDelimiter.self::$query.$fragmentDelimiter.$combination['value'],
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
                    $fragmentDelimiter.$combination['value'],
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
                    $pathDelimiter.self::$path.$fragmentDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => '',
                'fragment'        => $combination['valueNormalized'],
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $pathDelimiter.self::$pathNormalized.$combination['asPartOfFullString'],
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $pathDelimiter.self::$path.$queryDelimiter.self::$query.
                    $fragmentDelimiter.$invalidFragment,
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
                    $queryDelimiter.self::$query.$fragmentDelimiter.$invalidFragment,
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
                    $fragmentDelimiter.$invalidFragment,
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
                    $pathDelimiter.self::$path.$fragmentDelimiter.$invalidFragment,
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

        foreach (self::$fragmentValidCombinations as $combination) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $queryDelimiter.self::$query.$fragmentDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => '',
                'query'           => self::$queryNormalized,
                'fragment'        => $combination['valueNormalized'],
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $queryDelimiter.self::$queryNormalized.$combination['asPartOfFullString'],
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $fragmentDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => '',
                'query'           => '',
                'fragment'        => $combination['valueNormalized'],
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $combination['asPartOfFullString'],
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.self::$query.
                    $fragmentDelimiter.$invalidFragment,
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
                    $authorityDelimiter.self::$authority.$fragmentDelimiter.$invalidFragment,
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
     * Get values without query data set.
     */
    private static function getValuesWithoutQuery(): array
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;
        $result             = [];

        foreach (self::$fragmentValidCombinations as $combination) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$fragmentDelimiter.$combination['value'],
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathDelimiter.self::$pathNormalized,
                'query'           => '',
                'fragment'        => $combination['valueNormalized'],
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.$combination['asPartOfFullString'],
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $pathDelimiter.self::$path.$fragmentDelimiter.$invalidFragment,
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
