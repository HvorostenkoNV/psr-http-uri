<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\UserInfo;

use HNV\Http\Uri\Collection\UriSubDelimiters;
use HNV\Http\UriTests\ValuesProvider\UserInfo\{
    Login       as UserLoginValuesProvider,
    Password    as UserPasswordValuesProvider
};
use HNV\Http\UriTests\CombinationsProvider\{
    CombinationsProviderInterface,
    ValidValuesTrait
};

use function array_merge;
/** ***********************************************************************************************
 * URI user info in parsed parts different combinations provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class ParsedParts implements CombinationsProviderInterface
{
    use ValidValuesTrait;
    /** **********************************************************************
     * @inheritDoc
     *
     * @example
     *         [
     *          value           => login:password (full user info string),
     *          isValid         => true (user info string is valid and can be parsed),
     *          valueNormalized => login:password (full user info string in normalized form),
     *         ]
     ************************************************************************/
    public static function get(): array
    {
        self::initializeDefaultValues();

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

        foreach (UserLoginValuesProvider::getValidValues() as $login => $loginNormalized) {
            $result[]   = [
                'value'             => (string) $login,
                'isValid'           => true,
                'valueNormalized'   => $loginNormalized,
            ];
            $result[]   = [
                'value'             =>
                    $login.UriSubDelimiters::USER_INFO_SEPARATOR.
                    self::$password,
                'isValid'           => true,
                'valueNormalized'   =>
                    $loginNormalized.UriSubDelimiters::USER_INFO_SEPARATOR.
                    self::$passwordNormalized,
            ];
        }
        foreach (UserLoginValuesProvider::getInvalidValues() as $invalidLogin) {
            $result[]   = [
                'value'             =>
                    $invalidLogin.UriSubDelimiters::USER_INFO_SEPARATOR.
                    self::$password,
                'isValid'           => false,
                'valueNormalized'   => '',
            ];
            $result[]   = [
                'value'             => (string) $invalidLogin,
                'isValid'           => false,
                'valueNormalized'   => '',
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

        foreach (UserPasswordValuesProvider::getValidValues() as $password => $passwordNormalized) {
            $result[]   = [
                'value'             =>
                    self::$login.UriSubDelimiters::USER_INFO_SEPARATOR.
                    $password,
                'isValid'           => true,
                'valueNormalized'   =>
                    self::$loginNormalized.UriSubDelimiters::USER_INFO_SEPARATOR.
                    $passwordNormalized,
            ];
        }
        foreach (UserPasswordValuesProvider::getInvalidValues() as $invalidPassword) {
            $result[]   = [
                'value'             =>
                    self::$login.UriSubDelimiters::USER_INFO_SEPARATOR.
                    $invalidPassword,
                'isValid'           => false,
                'valueNormalized'   => '',
            ];
        }

        return $result;
    }
}