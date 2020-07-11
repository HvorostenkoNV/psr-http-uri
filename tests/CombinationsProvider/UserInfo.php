<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider;

use HNV\Http\UriTests\ValuesProvider\UserInfo\{
    Login       as UserLoginValuesProvider,
    Password    as UserPasswordValuesProvider
};
use HNV\Http\Uri\Collection\UriSubDelimiters;

use function key;
use function array_merge;
/** ***********************************************************************************************
 * URI user info different combinations provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UserInfo implements CombinationsProviderInterface
{
    private static array    $loginValidValues           = [];
    private static array    $loginInvalidValues         = [];
    private static array    $passwordValidValues        = [];
    private static array    $passwordInvalidValues      = [];

    private static string   $validLogin                 = '';
    private static string   $validLoginNormalized       = '';
    private static string   $validPassword              = '';
    private static string   $validPasswordNormalized    = '';
    /** **********************************************************************
     * @inheritDoc
     *
     * @example
     *         [
     *          login       => login,
     *          password    => password,
     *          value       => login:password,
     *         ]
     ************************************************************************/
    public static function get(): array
    {
        self::$loginValidValues         = UserLoginValuesProvider::getValidValues();
        self::$loginInvalidValues       = UserLoginValuesProvider::getInvalidValues();
        self::$passwordValidValues      = UserPasswordValuesProvider::getValidValues();
        self::$passwordInvalidValues    = UserPasswordValuesProvider::getInvalidValues();

        self::$validLogin               = (string)  key(self::$loginValidValues);
        self::$validLoginNormalized     = (string)  self::$loginValidValues[self::$validLogin];
        self::$validPassword            = (string)  key(self::$passwordValidValues);
        self::$validPasswordNormalized  = (string)  self::$passwordValidValues[self::$validPassword];

        return array_merge(
            self::getLoginCombinations(),
            self::getPasswordCombinations(),
        );
    }
    /** **********************************************************************
     * Get combinations with login values.
     *
     * @return  array                               Combinations data.
     ************************************************************************/
    private static function getLoginCombinations(): array {
        $result = [];

        foreach (self::$loginValidValues as $login => $loginNormalized) {
            $result[]   = [
                'login'     => (string) $login,
                'password'  => '',
                'value'     => (string) $loginNormalized,
            ];
            $result[]   = [
                'login'     => (string) $login,
                'password'  => self::$validPassword,
                'value'     =>
                    $loginNormalized.
                    UriSubDelimiters::USER_INFO_SEPARATOR.
                    self::$validPasswordNormalized,
            ];
        }
        foreach (self::$loginInvalidValues as $invalidLogin) {
            $result[]   = [
                'login'     => (string) $invalidLogin,
                'password'  => self::$validPassword,
                'value'     => '',
            ];
            $result[]   = [
                'login'     => (string) $invalidLogin,
                'password'  => '',
                'value'     => '',
            ];
        }

        return $result;
    }
    /** **********************************************************************
     * Get combinations with login values.
     *
     * @return  array                               Combinations data.
     ************************************************************************/
    private static function getPasswordCombinations(): array {
        $result = [];

        foreach (self::$passwordValidValues as $password => $passwordNormalized) {
            $result[]   = [
                'login'     => self::$validLogin,
                'password'  => (string) $password,
                'value'     =>
                    self::$validLoginNormalized.
                    UriSubDelimiters::USER_INFO_SEPARATOR.
                    $passwordNormalized,
            ];
            $result[]   = [
                'login'     => '',
                'password'  => (string) $password,
                'value'     => '',
            ];
        }
        foreach (self::$passwordInvalidValues as $invalidPassword) {
            $result[]   = [
                'login'     => self::$validLogin,
                'password'  => (string) $invalidPassword,
                'value'     => '',
            ];
            $result[]   = [
                'login'     => '',
                'password'  => (string) $invalidPassword,
                'value'     => '',
            ];
        }

        return $result;
    }
}