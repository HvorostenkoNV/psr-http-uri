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
 * URI authority different user info combinations builder.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UserInfoCombinations implements CombinationsProviderInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        $hostValidValues    = HostValuesProvider::getValidValues();
        $host               = key($hostValidValues);
        $hostNormalized     = $hostValidValues[$host];

        $portValidValues    = PortValuesProvider::getValidValues();
        $port               = key($portValidValues);
        $portNormalized     = $portValidValues[$port];

        $result             = [];

        foreach (UserInfoCombinationsProvider::get() as $combination) {
            if ($combination['value'] !== '') {
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => $host,
                    'port'      => $port,
                    'value'     =>
                        $combination['value'].UriGeneralDelimiters::USER_INFO_DELIMITER.
                        $hostNormalized.
                        UriGeneralDelimiters::PORT_DELIMITER.$portNormalized,
                ];
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => '',
                    'port'      => $port,
                    'value'     => '',
                ];
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => $host,
                    'port'      => 0,
                    'value'     =>
                        $combination['value'].UriGeneralDelimiters::USER_INFO_DELIMITER.
                        $hostNormalized,
                ];
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => '',
                    'port'      => 0,
                    'value'     => '',
                ];
            } else {
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => $host,
                    'port'      => $port,
                    'value'     =>
                        $hostNormalized.
                        UriGeneralDelimiters::PORT_DELIMITER.$portNormalized,
                ];
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => '',
                    'port'      => $port,
                    'value'     => '',
                ];
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => $host,
                    'port'      => 0,
                    'value'     => $hostNormalized,
                ];
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => '',
                    'port'      => 0,
                    'value'     => '',
                ];
            }
        }

        return $result;
    }
}