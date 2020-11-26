<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\FullString;

use HNV\Http\Uri\Collection\UriSubDelimiters;
use HNV\Http\UriTests\CombinationsProvider\Authority as AuthorityCombinationsProvider;
use HNV\Http\UriTests\ValuesProvider\{
    Scheme      as SchemeValuesProvider,
    Path        as PathValuesProvider,
    Query       as QueryValuesProvider,
    Fragment    as FragmentValuesProvider
};

use function strlen;
/** ***********************************************************************************************
 * URI full string different combinations abstract provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
abstract class AbstractFullString
{
    protected static string $scheme             = '';
    protected static string $schemeNormalized   = '';
    protected static string $login              = '';
    protected static string $password           = '';
    protected static string $host               = '';
    protected static int    $port               = 0;
    protected static string $authority          = '';
    protected static string $path               = '';
    protected static string $pathNormalized     = '';
    protected static string $query              = '';
    protected static string $queryNormalized    = '';
    protected static string $fragment           = '';
    protected static string $fragmentNormalized = '';
    /** **********************************************************************
     * Initialize default values.
     *
     * @return void
     ************************************************************************/
    protected static function initializeDefaultValues()
    {
        foreach (SchemeValuesProvider::getValidValues() as $scheme => $schemeNormalized) {
            if (strlen($scheme) > 0) {
                self::$scheme               = (string) $scheme;
                self::$schemeNormalized     = $schemeNormalized;
                break;
            }
        }

        foreach (AuthorityCombinationsProvider::get() as $combination) {
            if (strlen($combination['value']) > 0) {
                self::$login                = $combination['login'];
                self::$password             = $combination['password'];
                self::$host                 = $combination['host'];
                self::$port                 = $combination['port'];
                self::$authority            = $combination['value'];
                break;
            }
        }

        foreach (PathValuesProvider::getValidValues() as $path => $pathNormalized) {
            if (strlen($pathNormalized) > 0 && $path[0] !== UriSubDelimiters::PATH_PARTS_SEPARATOR) {
                self::$path                 = $path;
                self::$pathNormalized       = $pathNormalized;
                break;
            }
        }
        foreach (QueryValuesProvider::getValidValues() as $query => $queryNormalized) {
            if (strlen($query) > 0) {
                self::$query                = $query;
                self::$queryNormalized      = $queryNormalized;
                break;
            }
        }
        foreach (FragmentValuesProvider::getValidValues() as $fragment => $fragmentNormalized) {
            if (strlen($fragmentNormalized) > 0) {
                self::$fragment             = $fragment;
                self::$fragmentNormalized   = $fragmentNormalized;
                break;
            }
        }
    }
}