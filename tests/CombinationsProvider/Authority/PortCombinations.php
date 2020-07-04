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
 * URI authority different port combinations builder.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class PortCombinations implements CombinationsProviderInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        $hostValidValues    = HostValuesProvider::getValidValues();
        $host               = key($hostValidValues);
        $hostNormalized     = $hostValidValues[$host];

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

        foreach (PortValuesProvider::getValidValues() as $port => $portNormalized) {
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
                'host'      => '',
                'port'      => $port,
                'value'     => '',
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => $port,
                'value'     => '',
            ];
        }
        foreach (PortValuesProvider::getInvalidValues() as $invalidPort) {
            $result[]   = [
                'scheme'    => '',
                'login'     => $login,
                'password'  => $password,
                'host'      => $host,
                'port'      => $invalidPort,
                'value'     =>
                    $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $hostNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => $host,
                'port'      => $invalidPort,
                'value'     => $hostNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => $login,
                'password'  => $password,
                'host'      => '',
                'port'      => $invalidPort,
                'value'     => '',
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => $invalidPort,
                'value'     => '',
            ];
        }

        return $result;
    }
}