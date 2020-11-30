<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters
};
use HNV\Http\UriTests\CombinationsProvider\{
    CombinationsProviderInterface,
    FullStringDefaultValuesTrait
};
use HNV\Http\UriTests\CombinationsProvider\Authority as AuthorityCombinationsProvider;

use function strlen;
use function array_merge;
/** ***********************************************************************************************
 * URI full string different authority combinations provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class AuthorityCombinations implements CombinationsProviderInterface
{
    use FullStringDefaultValuesTrait;

    private static array $authorityValidCombinations                = [];
    private static array $authorityInvalidCombinations              = [];
    private static array $authorityValidCombinationsWithoutScheme   = [];
    private static array $authorityInvalidCombinationsWithoutScheme = [];
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
            $hasScheme              = strlen($combination['scheme'])    > 0;
            $validValue             = strlen($combination['value'])     > 0;
            $combinationWithScheme  = array_merge($combination, [
                'scheme'            => $hasScheme ? $combination['scheme'] : self::$scheme,
                'schemeNormalized'  => $hasScheme ? $combination['scheme'] : self::$schemeNormalized,
            ]);

            if ($validValue) {
                self::$authorityValidCombinations[]     = $combinationWithScheme;
            } else {
                self::$authorityInvalidCombinations[]   = $combinationWithScheme;
            }

            if (!$hasScheme) {
                if ($validValue) {
                    self::$authorityValidCombinationsWithoutScheme[]    = $combination;
                } else {
                    self::$authorityInvalidCombinationsWithoutScheme[]  = $combination;
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

        foreach (self::$authorityValidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
        }
        foreach (self::$authorityInvalidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
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

        foreach (self::$authorityValidCombinationsWithoutScheme as $combination) {
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
                'value'     => UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'],
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (self::$authorityInvalidCombinationsWithoutScheme as $combination) {
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => '',
                'value'     => self::$pathNormalized,
            ];
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
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

        foreach (self::$authorityValidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'],
            ];
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (self::$authorityInvalidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => '',
                'fragment'  => '',
                'value'     => '',
            ];
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => '',
                'value'     => '',
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

        foreach (self::$authorityValidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized,
            ];
        }
        foreach (self::$authorityInvalidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::FRAGMENT_DELIMITER.self::$fragmentNormalized,
            ];
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => '',
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized,
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

        foreach (self::$authorityValidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.$combination['value'].
                    UriSubDelimiters::PATH_PARTS_SEPARATOR.self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }
        foreach (self::$authorityInvalidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     =>
                    $combination['schemeNormalized'].UriGeneralDelimiters::SCHEME_DELIMITER.
                    self::$pathNormalized.
                    UriGeneralDelimiters::QUERY_DELIMITER.self::$queryNormalized,
            ];
        }

        return $result;
    }
}