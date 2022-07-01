<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString\CombinedValue;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters,
};
use HNV\Http\UriTests\CombinationsProvider\{
    CombinationsProviderInterface,
    AbstractCombinationsProvider,
};
use HNV\Http\UriTests\CombinationsProvider\Authority\CombinedValue as AuthorityCombinationsProvider;

use function strlen;
use function array_merge;
/** ***********************************************************************************************
 * URI full string different combination`s provider (authority combinations).
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class AuthorityCombinations extends AbstractCombinationsProvider implements CombinationsProviderInterface
{
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
     * @inheritDoc
     ************************************************************************/
    protected static function initializeDefaultValues(): void
    {
        parent::initializeDefaultValues();

        foreach (AuthorityCombinationsProvider::get() as $combination) {
            $hasScheme              = strlen($combination['scheme'])    > 0;
            $valueIsValid           = strlen($combination['value'])     > 0;
            $combinationModified    = array_merge($combination, [
                'authority' => $combination['value'],
            ]);
            $combinationWithScheme  = array_merge($combinationModified, [
                'scheme'            => $hasScheme ? $combination['scheme'] : self::$scheme,
                'schemeNormalized'  => $hasScheme ? $combination['scheme'] : self::$schemeNormalized,
            ]);

            $valueIsValid
                ? self::$authorityWithSchemeValidCombinations[]     = $combinationWithScheme
                : self::$authorityWithSchemeInvalidCombinations[]   = $combinationWithScheme;

            if (!$hasScheme) {
                $valueIsValid
                    ? self::$authorityWithoutSchemeValidCombinations[]      = $combinationModified
                    : self::$authorityWithoutSchemeInvalidCombinations[]    = $combinationModified;
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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER;
        $result             = [];

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    $authorityDelimiter.$combination['authority'].
                    $pathDelimiter.self::$pathNormalized.
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    self::$pathNormalized.$queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
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
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER;
        $result             = [];

        foreach (self::$authorityWithoutSchemeValidCombinations as $combination) {
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
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
                'query'     => self::$query,
                'fragment'  => '',
                'value'     => '',
            ];
        }
        foreach (self::$authorityWithoutSchemeInvalidCombinations as $combination) {
            $result[] = [
                'scheme'    => '',
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     => self::$pathNormalized.$queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
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
                'value'     => self::$pathNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
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
                'value'     => self::$pathNormalized.$queryDelimiter.self::$queryNormalized,
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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER;
        $result             = [];

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => '',
                'query'     => self::$query,
                'fragment'  => self::$fragment,
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    $authorityDelimiter.$combination['authority'].
                    $queryDelimiter.self::$queryNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
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
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    $authorityDelimiter.$combination['authority'].
                    $fragmentDelimiter.self::$fragmentNormalized,
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
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    $authorityDelimiter.$combination['authority'],
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
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    $authorityDelimiter.$combination['authority'].
                    $queryDelimiter.self::$queryNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR;
        $fragmentDelimiter  = UriGeneralDelimiters::FRAGMENT_DELIMITER;
        $result             = [];

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    $authorityDelimiter.$combination['authority'].
                    $pathDelimiter.self::$pathNormalized.
                    $fragmentDelimiter.self::$fragmentNormalized,
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
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    $authorityDelimiter.$combination['authority'].
                    $pathDelimiter.self::$pathNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => '',
                'fragment'  => self::$fragment,
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    self::$pathNormalized.$fragmentDelimiter.self::$fragmentNormalized,
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
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
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
        $schemeDelimiter    = UriGeneralDelimiters::SCHEME_DELIMITER;
        $authorityDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER;
        $pathDelimiter      = UriSubDelimiters::PATH_PARTS_SEPARATOR;
        $queryDelimiter     = UriGeneralDelimiters::QUERY_DELIMITER;
        $result             = [];

        foreach (self::$authorityWithSchemeValidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    $authorityDelimiter.$combination['authority'].
                    $pathDelimiter.self::$pathNormalized.$queryDelimiter.self::$queryNormalized,
            ];
        }
        foreach (self::$authorityWithSchemeInvalidCombinations as $combination) {
            $result[] = [
                'scheme'    => $combination['scheme'],
                'login'     => $combination['login'],
                'password'  => $combination['password'],
                'host'      => $combination['host'],
                'port'      => $combination['port'],
                'path'      => self::$path,
                'query'     => self::$query,
                'fragment'  => '',
                'value'     => $combination['schemeNormalized'].$schemeDelimiter.
                    self::$pathNormalized.$queryDelimiter.self::$queryNormalized,
            ];
        }

        return $result;
    }
}
