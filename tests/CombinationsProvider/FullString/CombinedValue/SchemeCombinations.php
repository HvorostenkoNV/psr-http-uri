<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\CombinedValue;

use HNV\Http\Uri\Collection\{
    AuthorityRules,
    FragmentRules,
    PathRules,
    QueryRules,
    SchemeRules,
};
use HNV\Http\UriTests\CombinationsProvider\{
    AbstractCombinationsProvider,
    CombinationsProviderInterface,
};
use HNV\Http\UriTests\ValuesProvider\Scheme as SchemeValuesProvider;

use function array_merge;

/**
 * URI full string different combinations provider (scheme combinations).
 */
class SchemeCombinations extends AbstractCombinationsProvider implements CombinationsProviderInterface
{
    private static array $schemeValidCombinations   = [];
    private static array $schemeInvalidCombinations = [];

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    protected static function initializeDefaultValues(): void
    {
        parent::initializeDefaultValues();

        self::$schemeValidCombinations   = SchemeValuesProvider::getValidValues();
        self::$schemeInvalidCombinations = SchemeValuesProvider::getInvalidValues();
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

        foreach (self::$schemeValidCombinations as $scheme => $schemeNormalized) {
            $result[] = [
                'scheme'   => $scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
        }
        foreach (self::$schemeInvalidCombinations as $invalidScheme) {
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => '',
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
        $queryDelimiter    = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;
        $result            = [];

        foreach (self::$schemeValidCombinations as $scheme => $schemeNormalized) {
            $result[] = [
                'scheme'   => $scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => $schemeNormalized.$schemeDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => self::$query,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => '',
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => $schemeNormalized.$schemeDelimiter.
                    self::$pathNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => '',
                'fragment' => '',
                'value'    => $schemeNormalized.$schemeDelimiter.self::$pathNormalized,
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => $schemeNormalized.$schemeDelimiter.
                    self::$pathNormalized.$queryDelimiter.self::$queryNormalized,
            ];
        }
        foreach (self::$schemeInvalidCombinations as $invalidScheme) {
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => self::$pathNormalized.$queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => self::$query,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => '',
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => self::$pathNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => '',
                'fragment' => '',
                'value'    => self::$pathNormalized,
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => self::$pathNormalized.$queryDelimiter.self::$queryNormalized,
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

        foreach (self::$schemeValidCombinations as $scheme => $schemeNormalized) {
            $result[] = [
                'scheme'   => $scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => self::$query,
                'fragment' => '',
                'value'    => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $queryDelimiter.self::$queryNormalized,
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => '',
                'fragment' => '',
                'value'    => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized,
            ];
        }
        foreach (self::$schemeInvalidCombinations as $invalidScheme) {
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => self::$query,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => '',
                'fragment' => '',
                'value'    => '',
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

        foreach (self::$schemeValidCombinations as $scheme => $schemeNormalized) {
            $result[] = [
                'scheme'   => $scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => $scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => '',
                'fragment' => '',
                'value'    => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized,
            ];
        }
        foreach (self::$schemeInvalidCombinations as $invalidScheme) {
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => '',
                'fragment' => '',
                'value'    => '',
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

        foreach (self::$schemeValidCombinations as $scheme => $schemeNormalized) {
            $result[] = [
                'scheme'   => $scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized,
            ];
        }
        foreach (self::$schemeInvalidCombinations as $invalidScheme) {
            $result[] = [
                'scheme'   => $invalidScheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => '',
            ];
        }

        return $result;
    }
}
