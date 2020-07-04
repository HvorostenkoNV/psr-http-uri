<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\Authority;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    SchemeStandardPorts
};
use HNV\Http\UriTests\CombinationsProvider\CombinationsProviderInterface;
use HNV\Http\UriTests\CombinationsProvider\UserInfo as UserInfoCombinationsProvider;
use HNV\Http\UriTests\ValuesProvider\Host           as HostValuesProvider;

use function key;
/** ***********************************************************************************************
 * URI authority different scheme with port combinations builder.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class SchemeWithPortCombinations implements CombinationsProviderInterface
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

        foreach (SchemeStandardPorts::get() as $port => $scheme) {
            $result[]   = [
                'scheme'    => $scheme,
                'login'     => $login,
                'password'  => $password,
                'host'      => $host,
                'port'      => $port,
                'value'     =>
                    $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $hostNormalized,
            ];
            $result[]   = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => $host,
                'port'      => $port,
                'value'     => $hostNormalized,
            ];
            $result[]   = [
                'scheme'    => $scheme,
                'login'     => $login,
                'password'  => $password,
                'host'      => '',
                'port'      => $port,
                'value'     => '',
            ];
            $result[]   = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => '',
                'port'      => $port,
                'value'     => '',
            ];
        }

        return $result;
    }
}