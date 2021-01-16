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
use HNV\Http\UriTests\ValuesProvider\Fragment as FragmentValuesProvider;

use function strlen;
use function str_contains;
use function array_merge;
/** ***********************************************************************************************
 * URI full string with it`s parsed parts provider (fragment combinations).
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class FragmentCombinations implements CombinationsProviderInterface
{
    use ValidValuesTrait;

    private static array $fragmentValidCombinations     = [];
    private static array $fragmentInvalidCombinations   = [];
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
            self::getValuesWithoutAuthority(),
            self::getValuesWithoutPath(),
            self::getValuesWithoutQuery(),
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
        foreach (FragmentValuesProvider::getValidValues() as $fragment => $fragmentNormalized) {
            $fragmentString = (string) $fragment;

            foreach (UriGeneralDelimiters::get() as $char) {
                if (str_contains($fragmentString, $char)) {
                    continue 2;
                }
            }

            self::$fragmentValidCombinations[] = [
                'value'                 => $fragmentString,
                'valueNormalized'       => $fragmentNormalized,
                'asPartOfFullString'    => strlen($fragmentNormalized) > 0
                    ? UriGeneralDelimiters::FRAGMENT_DELIMITER.$fragmentNormalized
                    : '',
            ];
        }

        self::$fragmentInvalidCombinations = FragmentValuesProvider::getInvalidValues();
    }
    /** **********************************************************************
     * Get full values data set.
     *
     * @return  array                       Data.
     ************************************************************************/
    private static function getFullValues(): array
    {
        $result = [];

        foreach (self::$fragmentValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => $combination['valueNormalized'],
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    $combination['asPartOfFullString'],
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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

        foreach (self::$fragmentValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
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
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => $combination['valueNormalized'],
                'valueNormalized'   =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    $combination['asPartOfFullString'],
            ];
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
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
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => '',
                'fragment'          => $combination['valueNormalized'],
                'valueNormalized'   =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $combination['asPartOfFullString'],
            ];
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
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
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'value'             =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
        $result = [];

        foreach (self::$fragmentValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => self::$queryNormalized,
                'fragment'          => $combination['valueNormalized'],
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    $combination['asPartOfFullString'],
            ];
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
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
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
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
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'authority'         => '',
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => '',
                'fragment'          => $combination['valueNormalized'],
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $combination['asPartOfFullString'],
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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

        foreach (self::$fragmentValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => '',
                'query'             => self::$queryNormalized,
                'fragment'          => $combination['valueNormalized'],
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    $combination['asPartOfFullString'],
            ];
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              => '',
                'query'             => '',
                'fragment'          => $combination['valueNormalized'],
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    $combination['asPartOfFullString'],
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$query.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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

        foreach (self::$fragmentValidCombinations as $combination) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$combination['value'],
                'isValid'           => true,
                'scheme'            => self::$schemeNormalized,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'authority'         => self::$authorityNormalized,
                'path'              =>
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.
                    self::$pathNormalized,
                'query'             => '',
                'fragment'          => $combination['valueNormalized'],
                'valueNormalized'   =>
                    self::$schemeNormalized.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authorityNormalized.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    $combination['asPartOfFullString'],
            ];
        }
        foreach (self::$fragmentInvalidCombinations as $invalidFragment) {
            $result[] = [
                'value'             =>
                    self::$scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.self::$authority.
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$path.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.$invalidFragment,
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