<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters,
};
use HNV\Http\UriTests\ValuesProvider\{
    Scheme              as SchemeValuesProvider,
    UserInfo\Login      as UserLoginValuesProvider,
    UserInfo\Password   as UserPasswordValuesProvider,
    Host                as HostValuesProvider,
    Port                as PortValuesProvider,
    Path                as PathValuesProvider,
    Query               as QueryValuesProvider,
    Fragment            as FragmentValuesProvider,
};

use function strlen;
use function str_starts_with;
/** ***********************************************************************************************
 * Abstract URI combinations provider.
 *
 * Generates basic valid values.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
abstract class AbstractCombinationsProvider
{
    protected static string $scheme                 = '';
    protected static string $schemeNormalized       = '';

    protected static string $login                  = '';
    protected static string $loginNormalized        = '';
    protected static string $password               = '';
    protected static string $passwordNormalized     = '';
    protected static string $userInfo               = '';
    protected static string $userInfoNormalized     = '';

    protected static string $host                   = '';
    protected static string $hostNormalized         = '';
    protected static int    $port                   = 0;
    protected static int    $portNormalized         = 0;
    protected static string $authority              = '';
    protected static string $authorityNormalized    = '';

    protected static string $path                   = '';
    protected static string $pathNormalized         = '';

    protected static string $query                  = '';
    protected static string $queryNormalized        = '';

    protected static string $fragment               = '';
    protected static string $fragmentNormalized     = '';
    /** **********************************************************************
     * Initialize default values.
     *
     * @return void
     ************************************************************************/
    protected static function initializeDefaultValues()
    {
        foreach (SchemeValuesProvider::getValidValues() as $scheme => $schemeNormalized) {
            if (strlen($scheme) > 0) {
                self::$scheme           = (string)  $scheme;
                self::$schemeNormalized = (string)  $schemeNormalized;
                break;
            }
        }

        foreach (UserLoginValuesProvider::getValidValues() as $login => $loginNormalized) {
            if (strlen($login) > 0) {
                self::$login            = (string)  $login;
                self::$loginNormalized  = (string)  $loginNormalized;
                break;
            }
        }

        foreach (UserPasswordValuesProvider::getValidValues() as $password => $passwordNormalized) {
            if (strlen($password) > 0) {
                self::$password             = (string)  $password;
                self::$passwordNormalized   = (string)  $passwordNormalized;
                break;
            }
        }

        foreach (HostValuesProvider::getValidValues() as $host => $hostNormalized) {
            if (strlen($host) > 0) {
                self::$host             = (string)  $host;
                self::$hostNormalized   = (string)  $hostNormalized;
                break;
            }
        }

        foreach (PortValuesProvider::getValidValues() as $port => $portNormalized) {
            if ($port > 0) {
                self::$port             = $port;
                self::$portNormalized   = $portNormalized;
                break;
            }
        }

        foreach (PathValuesProvider::getValidValues() as $path => $pathNormalized) {
            if (
                strlen($pathNormalized) > 0 &&
                !str_starts_with($path, UriSubDelimiters::PATH_PARTS_SEPARATOR)
            ) {
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

        self::$userInfo             =
            self::$login.
            UriSubDelimiters::USER_INFO_SEPARATOR.
            self::$password;
        self::$userInfoNormalized   =
            self::$loginNormalized.
            UriSubDelimiters::USER_INFO_SEPARATOR.
            self::$passwordNormalized;

        self::$authority            =
            self::$userInfo.
            UriGeneralDelimiters::USER_INFO_DELIMITER.
            self::$host.
            UriGeneralDelimiters::PORT_DELIMITER.
            self::$port;
        self::$authorityNormalized  =
            self::$userInfoNormalized.
            UriGeneralDelimiters::USER_INFO_DELIMITER.
            self::$hostNormalized.
            UriGeneralDelimiters::PORT_DELIMITER.
            self::$portNormalized;
    }
}
