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
        $loginValidValues           = UserLoginValuesProvider::getValidValues();
        $loginInvalidValues         = UserLoginValuesProvider::getInvalidValues();
        $passwordValidValues        = UserPasswordValuesProvider::getValidValues();
        $passwordInvalidValues      = UserPasswordValuesProvider::getInvalidValues();
        $validLogin                 = (string)  key($loginValidValues);
        $validLoginNormalized       = (string)  $loginValidValues[$validLogin];
        $validPassword              = (string)  key($passwordValidValues);
        $validPasswordNormalized    = (string)  $passwordValidValues[$validPassword];

        return array_merge(
            self::getLoginCombinations(
                $loginValidValues,
                $loginInvalidValues,
                $validPassword,
                $validPasswordNormalized
            ),
            self::getPasswordCombinations(
                $passwordValidValues,
                $passwordInvalidValues,
                $validLogin,
                $validLoginNormalized
            )
        );
    }
    /** **********************************************************************
     * Get combinations with login values.
     *
     * @param   array   $loginValidValues           Login valid values set.
     * @param   array   $loginInvalidValues         Login invalid values set.
     * @param   string  $validPassword              Valid password.
     * @param   string  $validPasswordNormalized    Valid normalized password.
     *
     * @return  array                               Combinations data.
     ************************************************************************/
    private static function getLoginCombinations(
        array   $loginValidValues,
        array   $loginInvalidValues,
        string  $validPassword,
        string  $validPasswordNormalized
    ): array {
        $result = [];

        foreach ($loginValidValues as $login => $loginNormalized) {
            $result[]   = [
                'login'     => (string) $login,
                'password'  => '',
                'value'     => (string) $loginNormalized,
            ];
            $result[]   = [
                'login'     => (string) $login,
                'password'  => $validPassword,
                'value'     =>
                    $loginNormalized.
                    UriSubDelimiters::USER_INFO_SEPARATOR.
                    $validPasswordNormalized,
            ];
        }
        foreach ($loginInvalidValues as $invalidLogin) {
            $result[]   = [
                'login'     => (string) $invalidLogin,
                'password'  => $validPassword,
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
     * @param   array   $passwordValidValues        Password valid values set.
     * @param   array   $passwordInvalidValues      Password invalid values set.
     * @param   string  $validLogin                 Valid login.
     * @param   string  $validLoginNormalized       Valid normalized login.
     *
     * @return  array                               Combinations data.
     ************************************************************************/
    private static function getPasswordCombinations(
        array   $passwordValidValues,
        array   $passwordInvalidValues,
        string  $validLogin,
        string  $validLoginNormalized
    ): array {
        $result = [];

        foreach ($passwordValidValues as $password => $passwordNormalized) {
            $result[]   = [
                'login'     => $validLogin,
                'password'  => (string) $password,
                'value'     =>
                    $validLoginNormalized.
                    UriSubDelimiters::USER_INFO_SEPARATOR.
                    $passwordNormalized,
            ];
            $result[]   = [
                'login'     => '',
                'password'  => (string) $password,
                'value'     => '',
            ];
        }
        foreach ($passwordInvalidValues as $invalidPassword) {
            $result[]   = [
                'login'     => $validLogin,
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