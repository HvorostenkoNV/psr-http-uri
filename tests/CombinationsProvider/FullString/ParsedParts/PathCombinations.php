<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\ParsedParts;

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
use function str_contains;
use function str_starts_with;

/**
 * URI full string with it`s parsed parts provider (path combinations).
 */
class PathCombinations extends AbstractCombinationsProvider implements CombinationsProviderInterface
{
    private static array $pathValidCombinations                 = [];
    private static array $pathInvalidCombinations               = [];
    private static array $pathValidCombinationsWithoutAuthority = [];

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
            foreach (UriGeneralDelimiters::cases() as $case) {
                if (str_contains($path, $case->value)) {
                    continue 2;
                }
            }

            $pathWithSeparatorPrefix = str_starts_with($path, $pathSeparator)
                ? $path
                : $pathSeparator.$path;
            $pathNormalizedWithSeparatorPrefix = str_starts_with($pathNormalized, $pathSeparator)
                ? $pathNormalized
                : $pathSeparator.$pathNormalized;
            $pathNormalizedWithMinimizedPrefix = str_starts_with($pathNormalized, $pathSeparator)
                ? $pathSeparator.ltrim($pathNormalized, $pathSeparator)
                : $pathNormalized;

            self::$pathValidCombinations[$pathWithSeparatorPrefix] = $pathNormalizedWithSeparatorPrefix;
            self::$pathValidCombinationsWithoutAuthority[$path]    = $pathNormalizedWithMinimizedPrefix;
        }

        self::$pathInvalidCombinations = PathValuesProvider::getInvalidValues();
    }

    /**
     * Get full values data set.
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
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$path.
                    $queryDelimiter.self::$query.$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$invalidPath.
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
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER->value;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result             = [];

        foreach (array_keys(self::$pathValidCombinations) as $path) {
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.$path.
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
                'value' => $authorityDelimiter.self::$authority.$path.
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
                'value' => $authorityDelimiter.self::$authority.$path.
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
                'value'           => $authorityDelimiter.self::$authority.$path,
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
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'value' => $authorityDelimiter.self::$authority.$invalidPath.
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
                'value' => $authorityDelimiter.self::$authority.$invalidPath.
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
                'value' => $authorityDelimiter.self::$authority.$invalidPath.
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
                'value'           => $authorityDelimiter.self::$authority.$invalidPath,
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
        $schemeDelimiter   = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $queryDelimiter    = UriGeneralDelimiters::QUERY_DELIMITER->value;
        $fragmentDelimiter = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result            = [];

        foreach (self::$pathValidCombinationsWithoutAuthority as $path => $pathNormalized) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.$path.
                    $queryDelimiter.self::$query.$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value' => $path.$queryDelimiter.self::$query.
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $pathNormalized.$queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'           => $path.$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathNormalized,
                'query'           => '',
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $pathNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'           => $path.$queryDelimiter.self::$query,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => '',
                'valueNormalized' => $pathNormalized.
                    $queryDelimiter.self::$queryNormalized,
            ];
            $result[] = [
                'value'           => $path,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathNormalized,
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => $pathNormalized,
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.$path.
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathNormalized,
                'query'           => '',
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.$pathNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.$path.
                    $queryDelimiter.self::$query,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => '',
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.$pathNormalized.
                    $queryDelimiter.self::$queryNormalized,
            ];
            $result[] = [
                'value'           => self::$scheme.$schemeDelimiter.$path,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => $pathNormalized,
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.$pathNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.$invalidPath.
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
                'value' => $invalidPath.$queryDelimiter.self::$query.
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
                'value'           => $invalidPath.$fragmentDelimiter.self::$fragment,
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
                'value'           => $invalidPath.$queryDelimiter.self::$query,
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
                'value'           => $invalidPath,
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
                'value' => self::$scheme.$schemeDelimiter.$invalidPath.
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
                'value' => self::$scheme.$schemeDelimiter.$invalidPath.
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
                'value'           => self::$scheme.$schemeDelimiter.$invalidPath,
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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result             = [];

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$path.$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathNormalized,
                'query'           => '',
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.$pathNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$path,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathNormalized,
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.$pathNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$invalidPath.
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
                    $authorityDelimiter.self::$authority.$invalidPath,
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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER->value;
        $result             = [];

        foreach (self::$pathValidCombinations as $path => $pathNormalized) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$path.$queryDelimiter.self::$query,
                'isValid'         => true,
                'scheme'          => self::$schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => '',
                'valueNormalized' => self::$schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.$pathNormalized.
                    $queryDelimiter.self::$queryNormalized,
            ];
        }
        foreach (self::$pathInvalidCombinations as $invalidPath) {
            $result[] = [
                'value' => self::$scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$invalidPath.
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
        }

        return $result;
    }
}
