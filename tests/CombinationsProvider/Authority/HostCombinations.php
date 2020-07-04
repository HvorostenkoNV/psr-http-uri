<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\Authority;

use HNV\Http\Uri\Collection\UriGeneralDelimiters;
use HNV\Http\UriTests\CombinationsProvider\CombinationsProviderInterface;
use HNV\Http\UriTests\CombinationsProvider\UserInfo as UserInfoCombinationsProvider;
use HNV\Http\UriTests\ValuesProvider\{
    Host    as HostValuesProvider,
    Port    as PortValuesProvider
};

use function key;
/** ***********************************************************************************************
 * URI authority different host combinations builder.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class HostCombinations implements CombinationsProviderInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        $portValidValues    = PortValuesProvider::getValidValues();
        $port               = key($portValidValues);
        $portNormalized     = $portValidValues[$port];

        $login              = '';
        $password           = '';
        $userInfo           = '';

        foreach (UserInfoCombinationsProvider::get() as $combination) {
            if (
                $combination['login'] !== ''    &&
                $combination['password'] !== '' &&
                $combination['value'] !== ''
            ) {
                $login      = $combination['login'];
                $password   = $combination['password'];
                $userInfo   = $combination['value'];
                break;
            }
        }

        $result             = [];

        foreach (HostValuesProvider::getValidValues() as $host => $hostNormalized) {
            $result[]   = [
                'scheme'    => '',
                'login'     => $login,
                'password'  => $password,
                'host'      => $host,
                'port'      => $port,
                'value'     =>
                    $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.$portNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => $host,
                'port'      => $port,
                'value'     =>
                    $hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.$portNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => $login,
                'password'  => $password,
                'host'      => $host,
                'port'      => 0,
                'value'     =>
                    $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $hostNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => $host,
                'port'      => 0,
                'value'     => $hostNormalized,
            ];
        }
        foreach (HostValuesProvider::getInvalidValues() as $invalidHost) {
            $result[]   = [
                'scheme'    => '',
                'login'     => $login,
                'password'  => $password,
                'host'      => $invalidHost,
                'port'      => $port,
                'value'     => '',
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => $invalidHost,
                'port'      => $port,
                'value'     => '',
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => $login,
                'password'  => $password,
                'host'      => $invalidHost,
                'port'      => 0,
                'value'     => '',
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => $invalidHost,
                'port'      => 0,
                'value'     => '',
            ];
        }

        return $result;
    }
}