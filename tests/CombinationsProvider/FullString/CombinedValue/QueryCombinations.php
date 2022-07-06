<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\CombinedValue;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters,
};
use HNV\Http\UriTests\CombinationsProvider\{
    AbstractCombinationsProvider,
    CombinationsProviderInterface,
};
use HNV\Http\UriTests\ValuesProvider\Query as QueryValuesProvider;

use function array_merge;
use function strlen;

/**
 * URI full string different combinations provider (query combinations).
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
            self::$queryValidCombinations[$query] = strlen($queryNormalized) > 0
                ? UriGeneralDelimiters::QUERY_DELIMITER->value.$queryNormalized
                : '';
        }

        self::$queryInvalidCombinations = QueryValuesProvider::getInvalidValues();
    }

    /**
     * Get full values data set.
     */
    private static function getFullValues(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR->value;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result             = [];

        foreach (self::$queryValidCombinations as $query => $queryNormalized) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => $query,
                'fragment' => self::$fragment,
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.
                    $queryNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => $invalidQuery,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
        }

        return $result;
    }

    /**
     * Get values without scheme data set.
     */
    private static function getValuesWithoutScheme(): array
    {
        $pathDelimiter     = UriSubDelimiters::PATH_PARTS_SEPARATOR->value;
        $fragmentDelimiter = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result            = [];

        foreach (self::$queryValidCombinations as $query => $queryNormalized) {
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => $query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => $query,
                'fragment' => self::$fragment,
                'value'    => self::$pathNormalized.
                    $queryNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => $query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => $query,
                'fragment' => '',
                'value'    => self::$pathNormalized.$queryNormalized,
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => $query,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => $query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => $query,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => $query,
                'fragment' => '',
                'value'    => '',
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => $invalidQuery,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => $invalidQuery,
                'fragment' => self::$fragment,
                'value'    => self::$pathNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => $invalidQuery,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => $invalidQuery,
                'fragment' => '',
                'value'    => $pathDelimiter.self::$pathNormalized,
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => $invalidQuery,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => $invalidQuery,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => $invalidQuery,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => $invalidQuery,
                'fragment' => '',
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
        $schemeDelimiter   = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $fragmentDelimiter = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result            = [];

        foreach (self::$queryValidCombinations as $query => $queryNormalized) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => $query,
                'fragment' => self::$fragment,
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    self::$pathNormalized.$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => $query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => $query,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => $query,
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    self::$pathNormalized.$queryNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => $invalidQuery,
                'fragment' => self::$fragment,
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    self::$pathNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => $invalidQuery,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => '',
                'query'    => $invalidQuery,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => self::$path,
                'query'    => $invalidQuery,
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.self::$pathNormalized,
            ];
        }

        return $result;
    }

    /**
     * Get values without path data set.
     */
    private static function getValuesWithoutPath(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result             = [];

        foreach (self::$queryValidCombinations as $query => $queryNormalized) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => $query,
                'fragment' => self::$fragment,
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $queryNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => $query,
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.$queryNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => $invalidQuery,
                'fragment' => self::$fragment,
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => '',
                'query'    => $invalidQuery,
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized,
            ];
        }

        return $result;
    }

    /**
     * Get values without fragment data set.
     */
    private static function getValuesWithoutFragment(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR->value;
        $result             = [];

        foreach (self::$queryValidCombinations as $query => $queryNormalized) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => $query,
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized.$queryNormalized,
            ];
        }
        foreach (self::$queryInvalidCombinations as $invalidQuery) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => self::$path,
                'query'    => $invalidQuery,
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathDelimiter.self::$pathNormalized,
            ];
        }

        return $result;
    }
}
