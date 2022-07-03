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
use HNV\Http\UriTests\ValuesProvider\Path as PathValuesProvider;

use function array_keys;
use function array_merge;
use function ltrim;
use function str_starts_with;

/**
 * URI full string different combination`s provider (path combinations).
 */
class PathCombinations extends AbstractCombinationsProvider implements CombinationsProviderInterface
{
    private static array $pathValidCombinations                 = [];
    private static array $pathInvalidCombinations               = [];
    private static array $pathWithoutAuthorityValidCombinations = [];

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

        $pathSeparator = UriSubDelimiters::PATH_PARTS_SEPARATOR->value;

        foreach (PathValuesProvider::getValidValues() as $path => $pathNormalized) {
            $startsWithSeparator = str_starts_with($pathNormalized, $pathSeparator);

            self::$pathValidCombinations[$path] = $startsWithSeparator
                ? $pathNormalized
                : $pathSeparator.$pathNormalized;
            self::$pathWithoutAuthorityValidCombinations[$path] = $startsWithSeparator
                ? $pathSeparator.ltrim($pathNormalized, $pathSeparator)
                : $pathNormalized;
        }

        self::$pathInvalidCombinations = PathValuesProvider::getInvalidValues();
    }

    /**
     * Get full values data set.
     *
     * @return array data
     */
    private static function getFullValues(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER->value;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result             = [];

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $path,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathNormalized.$queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $invalidPath,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
        }

        return $result;
    }

    /**
     * Get values without scheme data set.
     *
     * @return array data
     */
    private static function getValuesWithoutScheme(): array
    {
        $result = [];

        foreach (array_keys(self::$pathValidCombinations) as $path) {
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $path,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $path,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $path,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $path,
                'query'    => '',
                'fragment' => '',
                'value'    => '',
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $invalidPath,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $invalidPath,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $invalidPath,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $invalidPath,
                'query'    => '',
                'fragment' => '',
                'value'    => '',
            ];
        }

        return $result;
    }

    /**
     * Get values without authority data set.
     *
     * @return array data
     */
    private static function getValuesWithoutAuthority(): array
    {
        $schemeDelimiter   = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $queryDelimiter    = UriGeneralDelimiters::QUERY_DELIMITER->value;
        $fragmentDelimiter = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result            = [];

        foreach (self::$pathWithoutAuthorityValidCombinations as $path => $pathNormalized) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $path,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $pathNormalized.$queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $path,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => $pathNormalized.$queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $path,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => $pathNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $path,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => $pathNormalized.$queryDelimiter.self::$queryNormalized,
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $path,
                'query'    => '',
                'fragment' => '',
                'value'    => $pathNormalized,
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $path,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $pathNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $path,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $pathNormalized.$queryDelimiter.self::$queryNormalized,
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $path,
                'query'    => '',
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.$pathNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $invalidPath,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $invalidPath,
                'query'    => self::$query,
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $invalidPath,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $invalidPath,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => '',
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $invalidPath,
                'query'    => '',
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $invalidPath,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $invalidPath,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => '',
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => '',
                'password' => '',
                'host'     => '',
                'port'     => 0,
                'path'     => $invalidPath,
                'query'    => '',
                'fragment' => '',
                'value'    => '',
            ];
        }

        return $result;
    }

    /**
     * Get values without query data set.
     *
     * @return array data
     */
    private static function getValuesWithoutQuery(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result             = [];

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $path,
                'query'    => '',
                'fragment' => self::$fragment,
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $path,
                'query'    => '',
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.$pathNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $invalidPath,
                'query'    => '',
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
                'path'     => $invalidPath,
                'query'    => '',
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized,
            ];
        }

        return $result;
    }

    /**
     * Get values without fragment data set.
     *
     * @return array data
     */
    private static function getValuesWithoutFragment(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER->value;
        $result             = [];

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $path,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathNormalized.$queryDelimiter.self::$queryNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'scheme'   => self::$scheme,
                'login'    => self::$login,
                'password' => self::$password,
                'host'     => self::$host,
                'port'     => self::$port,
                'path'     => $invalidPath,
                'query'    => self::$query,
                'fragment' => '',
                'value'    => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $queryDelimiter.self::$queryNormalized,
            ];
        }

        return $result;
    }
}
