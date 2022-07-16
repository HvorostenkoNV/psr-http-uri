<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\ParsedParts;

use HNV\Http\Uri\Collection\{
    AuthorityRules,
    FragmentRules,
    PathRules,
    QueryRules,
    SchemeRules,
};
use HNV\Http\UriTests\CombinationsProvider\Authority\ParsedParts as AuthorityCombinationsProvider;
use HNV\Http\UriTests\CombinationsProvider\{
    AbstractCombinationsProvider,
    CombinationsProviderInterface,
};

use function array_merge;
use function strlen;

/**
 * URI full string with it`s parsed parts provider (authority combinations).
 */
class AuthorityCombinations extends AbstractCombinationsProvider implements CombinationsProviderInterface
{
    private static array $authorityWithSchemeValidCombinations      = [];
    private static array $authorityWithSchemeInvalidCombinations    = [];
    private static array $authorityWithoutSchemeValidCombinations   = [];
    private static array $authorityWithoutSchemeInvalidCombinations = [];

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
            self::getValuesWithoutPath(),
            self::getValuesWithoutQuery(),
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

        foreach (AuthorityCombinationsProvider::get() as $combination) {
            $hasScheme     = strlen($combination['scheme']) > 0;
            $validValue    = $combination['isValid'] === true;
            $schemePostfix = SchemeRules::URI_DELIMITER->value.
                AuthorityRules::URI_DELIMITER;
            $combinationWithScheme = array_merge($combination, [
                'value' => $hasScheme
                    ? $combination['value']
                    : self::$scheme.$schemePostfix.$combination['value'],
                'scheme' => $hasScheme
                    ? $combination['scheme']
                    : self::$schemeNormalized,
                'authority'       => $combination['valueNormalized'],
                'valueNormalized' => $hasScheme
                    ? $combination['scheme'].$schemePostfix.$combination['valueNormalized']
                    : self::$schemeNormalized.$schemePostfix.$combination['valueNormalized'],
            ]);

            $validValue
                ? self::$authorityWithSchemeValidCombinations[]   = $combinationWithScheme
                : self::$authorityWithSchemeInvalidCombinations[] = $combinationWithScheme;

            if (!$hasScheme) {
                $validValue
                    ? self::$authorityWithoutSchemeValidCombinations[]   = $combination
                    : self::$authorityWithoutSchemeInvalidCombinations[] = $combination;
            }
        }
    }

    /**
     * Get full values data set.
     */
    private static function getFullValues(): array
    {
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;
        $result             = [];

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'value' => $combination['value'].$pathPartsDelimiter.self::$path.
                    $queryDelimiter.self::$query.$fragmentDelimiter.self::$fragment,
                'isValid'   => true,
                'scheme'    => $combination['scheme'],
                'userInfo'  => $combination['userInfo'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'authority' => $combination['authority'],
                'path'      => $pathPartsDelimiter.
                    self::$pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $combination['valueNormalized'].
                    $pathPartsDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'value' => $combination['value'].$pathPartsDelimiter.self::$path.
                    $queryDelimiter.self::$query.$fragmentDelimiter.self::$fragment,
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
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;
        $result             = [];

        foreach (self::$authorityWithoutSchemeValidCombinations as $combination) {
            $result[] = [
                'value' => $authorityDelimiter.$combination['value'].
                    $pathPartsDelimiter.self::$path.$queryDelimiter.self::$query.
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
                'value' => $authorityDelimiter.$combination['value'].
                    $queryDelimiter.self::$query.$fragmentDelimiter.self::$fragment,
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
                'value' => $authorityDelimiter.$combination['value'].
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
                'value' => $authorityDelimiter.$combination['value'].
                    $queryDelimiter.self::$query,
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
                'value'           => $authorityDelimiter.$combination['value'],
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
                'value' => $authorityDelimiter.$combination['value'].
                    $pathPartsDelimiter.self::$path.$fragmentDelimiter.self::$fragment,
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
                'value' => $authorityDelimiter.$combination['value'].
                    $pathPartsDelimiter.self::$path,
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
                'value' => $authorityDelimiter.$combination['value'].
                    $pathPartsDelimiter.self::$path.$queryDelimiter.self::$query,
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
        foreach (self::$authorityWithoutSchemeInvalidCombinations as $combination) {
            $result[] = [
                'value' => $authorityDelimiter.$combination['value'].
                    $pathPartsDelimiter.self::$path.$queryDelimiter.self::$query.
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
                'value' => $authorityDelimiter.$combination['value'].
                    $queryDelimiter.self::$query.$fragmentDelimiter.self::$fragment,
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
                'value' => $authorityDelimiter.$combination['value'].
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
                'value' => $authorityDelimiter.$combination['value'].
                    $queryDelimiter.self::$query,
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
                'value'           => $authorityDelimiter.$combination['value'],
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
                'value' => $authorityDelimiter.$combination['value'].
                    $pathPartsDelimiter.self::$path.$fragmentDelimiter.self::$fragment,
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
                'value' => $authorityDelimiter.$combination['value'].
                    $pathPartsDelimiter.self::$path,
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
                'value' => $authorityDelimiter.$combination['value'].
                    $pathPartsDelimiter.self::$path.$queryDelimiter.self::$query,
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
        $queryDelimiter    = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;
        $result            = [];

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'value' => $combination['value'].
                    $queryDelimiter.self::$query.$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => $combination['scheme'],
                'userInfo'        => $combination['userInfo'],
                'host'            => $combination['host'],
                'port'            => $combination['port'],
                'authority'       => $combination['authority'],
                'path'            => '',
                'query'           => self::$queryNormalized,
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $combination['valueNormalized'].
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'           => $combination['value'].$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => $combination['scheme'],
                'userInfo'        => $combination['userInfo'],
                'host'            => $combination['host'],
                'port'            => $combination['port'],
                'authority'       => $combination['authority'],
                'path'            => '',
                'query'           => '',
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $combination['valueNormalized'].
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'           => $combination['value'],
                'isValid'         => true,
                'scheme'          => $combination['scheme'],
                'userInfo'        => $combination['userInfo'],
                'host'            => $combination['host'],
                'port'            => $combination['port'],
                'authority'       => $combination['authority'],
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => $combination['valueNormalized'],
            ];
            $result[] = [
                'value'           => $combination['value'].$queryDelimiter.self::$query,
                'isValid'         => true,
                'scheme'          => $combination['scheme'],
                'userInfo'        => $combination['userInfo'],
                'host'            => $combination['host'],
                'port'            => $combination['port'],
                'authority'       => $combination['authority'],
                'path'            => '',
                'query'           => self::$queryNormalized,
                'fragment'        => '',
                'valueNormalized' => $combination['valueNormalized'].
                    $queryDelimiter.self::$queryNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'value' => $combination['value'].$queryDelimiter.self::$query.
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
                'value' => $combination['value'].
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
                'value'           => $combination['value'],
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
                'value'           => $combination['value'].$queryDelimiter.self::$query,
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
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;
        $result             = [];

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'value' => $combination['value'].
                    $pathPartsDelimiter.self::$path.$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => $combination['scheme'],
                'userInfo'        => $combination['userInfo'],
                'host'            => $combination['host'],
                'port'            => $combination['port'],
                'authority'       => $combination['authority'],
                'path'            => $pathPartsDelimiter.self::$pathNormalized,
                'query'           => '',
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $combination['valueNormalized'].
                    $pathPartsDelimiter.self::$pathNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'           => $combination['value'].$pathPartsDelimiter.self::$path,
                'isValid'         => true,
                'scheme'          => $combination['scheme'],
                'userInfo'        => $combination['userInfo'],
                'host'            => $combination['host'],
                'port'            => $combination['port'],
                'authority'       => $combination['authority'],
                'path'            => $pathPartsDelimiter.self::$pathNormalized,
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => $combination['valueNormalized'].
                    $pathPartsDelimiter.self::$pathNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'value' => $combination['value'].$pathPartsDelimiter.self::$path.
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
                'value'           => $combination['value'].$pathPartsDelimiter.self::$path,
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
        $pathPartsDelimiter = PathRules::PARTS_SEPARATOR->value;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $result             = [];

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'value' => $combination['value'].
                    $pathPartsDelimiter.self::$path.$queryDelimiter.self::$query,
                'isValid'         => true,
                'scheme'          => $combination['scheme'],
                'userInfo'        => $combination['userInfo'],
                'host'            => $combination['host'],
                'port'            => $combination['port'],
                'authority'       => $combination['authority'],
                'path'            => $pathPartsDelimiter.self::$pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => '',
                'valueNormalized' => $combination['valueNormalized'].
                    $pathPartsDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'value' => $combination['value'].
                    $pathPartsDelimiter.self::$path.$queryDelimiter.self::$query,
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
