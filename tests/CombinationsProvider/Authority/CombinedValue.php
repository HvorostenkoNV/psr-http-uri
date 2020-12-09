<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\Authority;

use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    SchemeStandardPorts
};
use HNV\Http\UriTests\CombinationsProvider\{
    CombinationsProviderInterface,
    ValidValuesTrait
};
use HNV\Http\UriTests\CombinationsProvider\UserInfo\CombinedValue as UserInfoCombinationsProvider;
use HNV\Http\UriTests\ValuesProvider\{
    Host    as HostValuesProvider,
    Port    as PortValuesProvider
};

use function strlen;
use function array_merge;
/** ***********************************************************************************************
 * URI authority different combinations provider.
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
     *          scheme      => http,
     *          login       => login,
     *          password    => password,
     *          host        => site.com,
     *          port        => 10,
     *          value       => login:password@site.com:10 (full authority string),
     *         ]
     ************************************************************************/
    public static function get(): array
    {
        $result = [];

        self::initializeDefaultValues();

        foreach ([
            self::getUserInfoCombinations(),
            self::getHostCombinations(),
            self::getPortCombinations(),
            self::getSchemeWithPortCombinations(),
        ] as $dataSet) {
            $result = array_merge($result, $dataSet);
        }

        return $result;
    }
    /** **********************************************************************
     * Get user info combinations.
     *
     * @return array                        Combinations data.
     ************************************************************************/
    private static function getUserInfoCombinations(): array
    {
        $result = [];

        foreach (UserInfoCombinationsProvider::get() as $combination) {
            if (strlen($combination['value']) > 0) {
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => self::$host,
                    'port'      => self::$port,
                    'value'     =>
                        $combination['value'].UriGeneralDelimiters::USER_INFO_DELIMITER.
                        self::$hostNormalized.
                        UriGeneralDelimiters::PORT_DELIMITER.self::$portNormalized,
                ];
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => '',
                    'port'      => self::$port,
                    'value'     => '',
                ];
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => self::$host,
                    'port'      => 0,
                    'value'     =>
                        $combination['value'].UriGeneralDelimiters::USER_INFO_DELIMITER.
                        self::$hostNormalized,
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
                    'host'      => self::$host,
                    'port'      => self::$port,
                    'value'     =>
                        self::$hostNormalized.
                        UriGeneralDelimiters::PORT_DELIMITER.self::$portNormalized,
                ];
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => '',
                    'port'      => self::$port,
                    'value'     => '',
                ];
                $result[]   = [
                    'scheme'    => '',
                    'login'     => $combination['login'],
                    'password'  => $combination['password'],
                    'host'      => self::$host,
                    'port'      => 0,
                    'value'     => self::$hostNormalized,
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
    /** **********************************************************************
     * Get host combinations.
     *
     * @return array                        Combinations data.
     ************************************************************************/
    private static function getHostCombinations(): array
    {
        $result = [];

        foreach (HostValuesProvider::getValidValues() as $host => $hostNormalized) {
            $result[]   = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => $host,
                'port'      => self::$port,
                'value'     =>
                    self::$userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$portNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => $host,
                'port'      => self::$port,
                'value'     =>
                    $hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$portNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => $host,
                'port'      => 0,
                'value'     =>
                    self::$userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
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
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => $invalidHost,
                'port'      => self::$port,
                'value'     => '',
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => $invalidHost,
                'port'      => self::$port,
                'value'     => '',
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
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
    /** **********************************************************************
     * Get port combinations.
     *
     * @return array                        Combinations data.
     ************************************************************************/
    private static function getPortCombinations(): array
    {
        $result = [];

        foreach (PortValuesProvider::getValidValues() as $port => $portNormalized) {
            $result[]   = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => $port,
                'value'     =>
                    self::$userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.$portNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => self::$host,
                'port'      => $port,
                'value'     =>
                    self::$hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.$portNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
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
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => $invalidPort,
                'value'     =>
                    self::$userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$hostNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => '',
                'password'  => '',
                'host'      => self::$host,
                'port'      => $invalidPort,
                'value'     => self::$hostNormalized,
            ];
            $result[]   = [
                'scheme'    => '',
                'login'     => self::$login,
                'password'  => self::$password,
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
    /** **********************************************************************
     * Get scheme with port combinations.
     *
     * @return array                        Combinations data.
     ************************************************************************/
    private static function getSchemeWithPortCombinations(): array
    {
        $result = [];

        foreach (SchemeStandardPorts::get() as $port => $scheme) {
            $result[]   = [
                'scheme'    => $scheme,
                'login'     => self::$login,
                'password'  => self::$password,
                'host'      => self::$host,
                'port'      => $port,
                'value'     =>
                    self::$userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$hostNormalized,
            ];
            $result[]   = [
                'scheme'    => $scheme,
                'login'     => '',
                'password'  => '',
                'host'      => self::$host,
                'port'      => $port,
                'value'     => self::$hostNormalized,
            ];
            $result[]   = [
                'scheme'    => $scheme,
                'login'     => self::$login,
                'password'  => self::$password,
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