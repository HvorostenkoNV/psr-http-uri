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
use HNV\Http\UriTests\ValuesProvider\Scheme as SchemeValuesProvider;

use function array_merge;

/**
 * URI full string with it`s parsed parts provider (scheme combinations).
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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $pathPartsDelimiter = UriSubDelimiters::PATH_PARTS_SEPARATOR->value;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER->value;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result             = [];

        foreach (self::$schemeValidCombinations as $scheme => $schemeNormalized) {
            $result[] = [
                'value' => $scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathPartsDelimiter.self::$path.
                    $queryDelimiter.self::$query.$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathPartsDelimiter.self::$pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathPartsDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
        }
        foreach (self::$schemeInvalidCombinations as $invalidScheme) {
            $result[] = [
                'value' => $invalidScheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathPartsDelimiter.self::$path.
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
     * Get values without authority data set.
     */
    private static function getValuesWithoutAuthority(): array
    {
        $schemeDelimiter   = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $queryDelimiter    = UriGeneralDelimiters::QUERY_DELIMITER->value;
        $fragmentDelimiter = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result            = [];

        foreach (self::$schemeValidCombinations as $scheme => $schemeNormalized) {
            $result[] = [
                'value' => $scheme.$schemeDelimiter.self::$path.
                    $queryDelimiter.self::$query.$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => self::$pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value' => $scheme.$schemeDelimiter.
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
                'value' => $scheme.$schemeDelimiter.
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
                'value'           => $scheme.$schemeDelimiter.$queryDelimiter.self::$query,
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
                'value'           => $scheme.$schemeDelimiter,
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
                'value' => $scheme.$schemeDelimiter.self::$path.
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => self::$pathNormalized,
                'query'           => '',
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.
                    self::$pathNormalized.$fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'           => $scheme.$schemeDelimiter.self::$path,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => self::$pathNormalized,
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.self::$pathNormalized,
            ];
            $result[] = [
                'value' => $scheme.$schemeDelimiter.self::$path.
                    $queryDelimiter.self::$query,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'authority'       => '',
                'path'            => self::$pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => '',
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.
                    self::$pathNormalized.$queryDelimiter.self::$queryNormalized,
            ];
        }
        foreach (self::$schemeInvalidCombinations as $invalidScheme) {
            $result[] = [
                'value' => $invalidScheme.$schemeDelimiter.self::$path.
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
                'value' => $invalidScheme.$schemeDelimiter.
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
                'value' => $invalidScheme.$schemeDelimiter.
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
                'value' => $invalidScheme.$schemeDelimiter.
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
                'value'           => $invalidScheme.$schemeDelimiter,
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
                'value' => $invalidScheme.$schemeDelimiter.self::$path.
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
                'value'           => $invalidScheme.$schemeDelimiter.self::$path,
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
                'value' => $invalidScheme.$schemeDelimiter.self::$path.
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

    /**
     * Get values without path data set.
     */
    private static function getValuesWithoutPath(): array
    {
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER->value;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result             = [];

        foreach (self::$schemeValidCombinations as $scheme => $schemeNormalized) {
            $result[] = [
                'value' => $scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.self::$query.
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => '',
                'query'           => self::$queryNormalized,
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value' => $scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => '',
                'query'           => '',
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value' => $scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.self::$query,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => '',
                'query'           => self::$queryNormalized,
                'fragment'        => '',
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $queryDelimiter.self::$queryNormalized,
            ];
            $result[] = [
                'value' => $scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => '',
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized,
            ];
        }
        foreach (self::$schemeInvalidCombinations as $invalidScheme) {
            $result[] = [
                'value' => $invalidScheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.
                    self::$query.$fragmentDelimiter.self::$fragment,
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
                'value' => $invalidScheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$fragmentDelimiter.self::$fragment,
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
                'value' => $invalidScheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$queryDelimiter.self::$query,
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
                'value' => $invalidScheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority,
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
        $pathPartsDelimiter = UriSubDelimiters::PATH_PARTS_SEPARATOR->value;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER->value;
        $result             = [];

        foreach (self::$schemeValidCombinations as $scheme => $schemeNormalized) {
            $result[] = [
                'value' => $scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathPartsDelimiter.self::$path.
                    $fragmentDelimiter.self::$fragment,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathPartsDelimiter.self::$pathNormalized,
                'query'           => '',
                'fragment'        => self::$fragmentNormalized,
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathPartsDelimiter.self::$pathNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
            $result[] = [
                'value' => $scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathPartsDelimiter.self::$path,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathPartsDelimiter.self::$pathNormalized,
                'query'           => '',
                'fragment'        => '',
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathPartsDelimiter.self::$pathNormalized,
            ];
        }
        foreach (self::$schemeInvalidCombinations as $invalidScheme) {
            $result[] = [
                'value' => $invalidScheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathPartsDelimiter.self::$path.
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
                'value' => $invalidScheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathPartsDelimiter.self::$path,
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
        $pathPartsDelimiter = UriSubDelimiters::PATH_PARTS_SEPARATOR->value;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER->value;
        $result             = [];

        foreach (self::$schemeValidCombinations as $scheme => $schemeNormalized) {
            $result[] = [
                'value' => $scheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.
                    $pathPartsDelimiter.self::$path.$queryDelimiter.self::$query,
                'isValid'         => true,
                'scheme'          => $schemeNormalized,
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'authority'       => self::$authorityNormalized,
                'path'            => $pathPartsDelimiter.self::$pathNormalized,
                'query'           => self::$queryNormalized,
                'fragment'        => '',
                'valueNormalized' => $schemeNormalized.$schemeDelimiter.
                    $authorityDelimiter.self::$authorityNormalized.
                    $pathPartsDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized,
            ];
        }
        foreach (self::$schemeInvalidCombinations as $invalidScheme) {
            $result[] = [
                'value' => $invalidScheme.$schemeDelimiter.
                    $authorityDelimiter.self::$authority.$pathPartsDelimiter.self::$path.
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
