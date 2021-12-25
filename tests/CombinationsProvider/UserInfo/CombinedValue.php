<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\UserInfo;

use HNV\Http\Uri\Collection\UriSubDelimiters;
use HNV\Http\UriTests\ValuesProvider\UserInfo\{
    Login       as UserLoginValuesProvider,
    Password    as UserPasswordValuesProvider,
};
use HNV\Http\UriTests\CombinationsProvider\{
    CombinationsProviderInterface,
    AbstractCombinationsProvider,
};

use function array_merge;
/** ***********************************************************************************************
 * URI user info different combination`s provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class CombinedValue extends AbstractCombinationsProvider implements CombinationsProviderInterface
{
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
    private static function getLoginCombinations(): array
    {
        $delimiter  = UriSubDelimiters::USER_INFO_SEPARATOR;
        $result     = [];

        foreach (UserLoginValuesProvider::getValidValues() as $login => $loginNormalized) {
            $result[] = [
                'login'     => (string) $login,
                'password'  => '',
                'value'     => (string) $loginNormalized,
            ];
            $result[] = [
                'login'     => (string) $login,
                'password'  => self::$password,
                'value'     => $loginNormalized.$delimiter.self::$passwordNormalized,
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
    private static function getPasswordCombinations(): array
    {
        $delimiter  = UriSubDelimiters::USER_INFO_SEPARATOR;
        $result     = [];

        foreach (UserPasswordValuesProvider::getValidValues() as $password => $passwordNormalized) {
            $result[] = [
                'login'     => self::$login,
                'password'  => (string) $password,
                'value'     => self::$loginNormalized.$delimiter.$passwordNormalized,
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
