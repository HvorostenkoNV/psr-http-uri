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
 * URI user info different combinations provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class CombinedValue implements CombinationsProviderInterface
{
    use ValidValuesTrait;
    /** **********************************************************************
     * @inheritDoc
     *
     * @example
     *         [
     *          login       => login,
     *          password    => password,
     *          value       => login:password (full user info string),
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
            $result[] = [
                'login'     => (string) $login,
                'password'  => '',
                'value'     => (string) $loginNormalized,
            ];
            $result[] = [
                'login'     => (string) $login,
                'password'  => self::$password,
                'value'     =>
                    $loginNormalized.
                    UriSubDelimiters::USER_INFO_SEPARATOR.
                    self::$passwordNormalized,
            ];
        }
        foreach (UserLoginValuesProvider::getInvalidValues() as $invalidLogin) {
            $result[] = [
                'login'     => (string) $invalidLogin,
                'password'  => self::$password,
                'value'     => '',
            ];
            $result[] = [
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

        foreach (UserPasswordValuesProvider::getValidValues() as $password => $passwordNormalized) {
            $result[] = [
                'login'     => self::$login,
                'password'  => (string) $password,
                'value'     =>
                    self::$loginNormalized.
                    UriSubDelimiters::USER_INFO_SEPARATOR.
                    $passwordNormalized,
            ];
            $result[] = [
                'login'     => '',
                'password'  => (string) $password,
                'value'     => '',
            ];
        }
        foreach (UserPasswordValuesProvider::getInvalidValues() as $invalidPassword) {
            $result[] = [
                'login'     => self::$login,
                'password'  => (string) $invalidPassword,
                'value'     => '',
            ];
            $result[] = [
                'login'     => '',
                'password'  => (string) $invalidPassword,
                'value'     => '',
            ];
        }

        return $result;
    }
}