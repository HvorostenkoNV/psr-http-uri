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
use HNV\Http\UriTests\CombinationsProvider\Authority\ParsedParts as AuthorityCombinationsProvider;

use function strlen;
use function array_merge;
/** ***********************************************************************************************
 * URI full string with it`s parsed parts provider (authority combinations).
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class AuthorityCombinations implements CombinationsProviderInterface
{
    use ValidValuesTrait;

    private static array $authorityWithSchemeValidCombinations      = [];
    private static array $authorityWithSchemeInvalidCombinations    = [];
    private static array $authorityWithoutSchemeValidCombinations   = [];
    private static array $authorityWithoutSchemeInvalidCombinations = [];
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
            self::getValuesWithoutPath(),
            self::getValuesWithoutQuery(),
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
        foreach (AuthorityCombinationsProvider::get() as $combination) {
            $hasScheme              = strlen($combination['scheme']) > 0;
            $validValue             = $combination['isValid'] === true;
            $schemePostfix          =
                UriGeneralDelimiters::SCHEME_DELIMITER.
                UriGeneralDelimiters::AUTHORITY_DELIMITER;
            $combinationWithScheme  = array_merge($combination, [
                'value'             => $hasScheme
                    ? $combination['value']
                    : self::$scheme.$schemePostfix.$combination['value'],
                'scheme'            => $hasScheme
                    ? $combination['scheme']
                    : self::$schemeNormalized,
                'authority'         => $combination['valueNormalized'],
                'valueNormalized'   => $hasScheme
                    ? $combination['value']
                    : self::$schemeNormalized.$schemePostfix.$combination['valueNormalized'],
            ]);

            if ($validValue) {
                self::$authorityWithSchemeValidCombinations[]   = $combinationWithScheme;
            } else {
                self::$authorityWithSchemeInvalidCombinations[] = $combinationWithScheme;
            }

            if (!$hasScheme) {
                if ($validValue) {
                    self::$authorityWithoutSchemeValidCombinations[]    = $combination;
                } else {
                    self::$authorityWithoutSchemeInvalidCombinations[]  = $combination;
                }
            }
        }
    }
    /** **********************************************************************
     * Get full values data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getFullValues(): array
    {
        $result = [];

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => $combination['scheme'],
                'userInfo'          => $combination['userInfo'],
                'host'              => $combination['host'],
                'port'              => $combination['port'],
                'authority'         => $combination['authority'],
                'path'              => self::$pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    $combination['valueNormalized'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
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

        foreach (self::$authorityWithoutSchemeValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'],
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path,
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
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
        foreach (self::$authorityWithoutSchemeInvalidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'],
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path,
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
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

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => $combination['scheme'],
                'userInfo'          => $combination['userInfo'],
                'host'              => $combination['host'],
                'port'              => $combination['port'],
                'authority'         => $combination['authority'],
                'path'              => '',
                'query'             => self::$queryNormalized,
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    $combination['valueNormalized'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => $combination['scheme'],
                'userInfo'          => $combination['userInfo'],
                'host'              => $combination['host'],
                'port'              => $combination['port'],
                'authority'         => $combination['authority'],
                'path'              => '',
                'query'             => '',
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    $combination['valueNormalized'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'             => $combination['value'],
                'isValid'           => true,
                'scheme'            => $combination['scheme'],
                'userInfo'          => $combination['userInfo'],
                'host'              => $combination['host'],
                'port'              => $combination['port'],
                'authority'         => $combination['authority'],
                'path'              => '',
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   => $combination['valueNormalized'],
            ];
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'           => true,
                'scheme'            => $combination['scheme'],
                'userInfo'          => $combination['userInfo'],
                'host'              => $combination['host'],
                'port'              => $combination['port'],
                'authority'         => $combination['authority'],
                'path'              => '',
                'query'             => self::$queryNormalized,
                'fragment'          => '',
                'valueNormalized'   =>
                    $combination['valueNormalized'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
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
                    $combination['value'].
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
                'value'             => $combination['value'],
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
                    $combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
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
     * Get values without query data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getValuesWithoutQuery(): array
    {
        $result = [];

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragment,
                'isValid'           => true,
                'scheme'            => $combination['scheme'],
                'userInfo'          => $combination['userInfo'],
                'host'              => $combination['host'],
                'port'              => $combination['port'],
                'authority'         => $combination['authority'],
                'path'              => self::$pathNormalized,
                'query'             => '',
                'fragment'          => self::$fragmentNormalized,
                'valueNormalized'   =>
                    $combination['valueNormalized'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path,
                'isValid'           => true,
                'scheme'            => $combination['scheme'],
                'userInfo'          => $combination['userInfo'],
                'host'              => $combination['host'],
                'port'              => $combination['port'],
                'authority'         => $combination['authority'],
                'path'              => self::$pathNormalized,
                'query'             => '',
                'fragment'          => '',
                'valueNormalized'   =>
                    $combination['valueNormalized'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
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
                    $combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path,
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

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
                'isValid'           => true,
                'scheme'            => $combination['scheme'],
                'userInfo'          => $combination['userInfo'],
                'host'              => $combination['host'],
                'port'              => $combination['port'],
                'authority'         => $combination['authority'],
                'path'              => self::$pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => '',
                'valueNormalized'   =>
                    $combination['valueNormalized'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    $combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query,
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