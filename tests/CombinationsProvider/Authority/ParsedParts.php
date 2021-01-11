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
use HNV\Http\UriTests\CombinationsProvider\UserInfo\ParsedParts as UserInfoCombinationsProvider;
use HNV\Http\UriTests\ValuesProvider\{
    Host    as HostValuesProvider,
    Port    as PortValuesProvider
};

use function str_contains;
use function array_merge;
/** ***********************************************************************************************
 * URI authority in parsed parts different combinations provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class ParsedParts implements CombinationsProviderInterface
{
    use ValidValuesTrait;

    private static array $userInfoValidValues   = [];
    private static array $userInfoInvalidValues = [];
    /** **********************************************************************
     * @inheritDoc
     *
     * @example
     *         [
     *          value               => login:password@site.com:10 (full authority string),
     *          isValid             => true (authority string is valid and can be parsed),
     *          scheme              => http,
     *          userInfo            => login:password,
     *          host                => site.com,
     *          port                => 10,
     *          valueNormalized     => login:password@site.com:10 (full authority string in normalized form),
     *         ]
     ************************************************************************/
    public static function get(): array
    {
        $result = [];

        self::initializeDefaultValues();
        self::initializeWorkableValues();

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
     * Initialize workable values.
     *
     * @return void
     ************************************************************************/
    private static function initializeWorkableValues()
    {
        foreach (UserInfoCombinationsProvider::get() as $combination) {
            if ($combination['isValid']) {
                $value              = $combination['value'];
                $valueNormalized    = $combination['valueNormalized'];

                foreach (UriGeneralDelimiters::get() as $char) {
                    if (str_contains($value, $char)) {
                        $value = $valueNormalized;
                        break;
                    }
                }

                self::$userInfoValidValues[$value] = $valueNormalized;
            } else {
                self::$userInfoInvalidValues[] = $combination['value'];
            }
        }
    }
    /** **********************************************************************
     * Get user info combinations.
     *
     * @return array                        Combinations data.
     ************************************************************************/
    private static function getUserInfoCombinations(): array
    {
        $result = [];

        foreach (self::$userInfoValidValues as $userInfo => $userInfoNormalized) {
            $result[] = [
                'value'             =>
                    $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$host.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$port,
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => $userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => self::$portNormalized,
                'valueNormalized'   =>
                    $userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$portNormalized,
            ];
            $result[] = [
                'value'             =>
                    $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$port,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$host,
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => $userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => 0,
                'valueNormalized'   =>
                    $userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$hostNormalized,
            ];
            $result[] = [
                'value'             =>
                    $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
        }
        foreach (self::$userInfoInvalidValues as $invalidUserInfo) {
            $result[] = [
                'value'             =>
                    $invalidUserInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$host.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$port,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    $invalidUserInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$port,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    $invalidUserInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$host,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    $invalidUserInfo.UriGeneralDelimiters::USER_INFO_DELIMITER,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
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
            $result[] = [
                'value'             =>
                    self::$userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $host.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$port,
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => self::$userInfoNormalized,
                'host'              => $hostNormalized,
                'port'              => self::$portNormalized,
                'valueNormalized'   =>
                    self::$userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$portNormalized,
            ];
            $result[] = [
                'value'             =>
                    $host.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$port,
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => $hostNormalized,
                'port'              => self::$portNormalized,
                'valueNormalized'   =>
                    $hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$portNormalized,
            ];
            $result[] = [
                'value'             =>
                    self::$userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $host,
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => self::$userInfoNormalized,
                'host'              => $hostNormalized,
                'port'              => 0,
                'valueNormalized'   =>
                    self::$userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $hostNormalized,
            ];
            $result[] = [
                'value'             => $host,
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => $hostNormalized,
                'port'              => 0,
                'valueNormalized'   => $hostNormalized,
            ];
        }
        foreach (HostValuesProvider::getInvalidValues() as $invalidHost) {
            $result[] = [
                'value'             =>
                    self::$userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $invalidHost.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$port,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    $invalidHost.
                    UriGeneralDelimiters::PORT_DELIMITER.self::$port,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    self::$userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    $invalidHost,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             => $invalidHost,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
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
            $result[] = [
                'value'             =>
                    self::$userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$host.
                    UriGeneralDelimiters::PORT_DELIMITER.$port,
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => $portNormalized,
                'valueNormalized'   =>
                    self::$userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.$portNormalized,
            ];
            $result[] = [
                'value'             =>
                    self::$host.
                    UriGeneralDelimiters::PORT_DELIMITER.$port,
                'isValid'           => true,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => self::$hostNormalized,
                'port'              => $portNormalized,
                'valueNormalized'   =>
                    self::$hostNormalized.
                    UriGeneralDelimiters::PORT_DELIMITER.$portNormalized,
            ];
            $result[] = [
                'value'             =>
                    self::$userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    UriGeneralDelimiters::PORT_DELIMITER.$port,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             => UriGeneralDelimiters::PORT_DELIMITER.$port,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
        }
        foreach (PortValuesProvider::getInvalidValues() as $invalidPort) {
            $result[] = [
                'value'             =>
                    self::$userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$host.
                    UriGeneralDelimiters::PORT_DELIMITER.$invalidPort,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    self::$host.
                    UriGeneralDelimiters::PORT_DELIMITER.$invalidPort,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    self::$userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    UriGeneralDelimiters::PORT_DELIMITER.$invalidPort,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             => UriGeneralDelimiters::PORT_DELIMITER.$invalidPort,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
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
            $result[] = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.
                    self::$userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$host.
                    UriGeneralDelimiters::PORT_DELIMITER.$port,
                'isValid'           => true,
                'scheme'            => $scheme,
                'userInfo'          => self::$userInfoNormalized,
                'host'              => self::$hostNormalized,
                'port'              => 0,
                'valueNormalized'   =>
                    self::$userInfoNormalized.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    self::$hostNormalized,
            ];
            $result[] = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.
                    self::$host.
                    UriGeneralDelimiters::PORT_DELIMITER.$port,
                'isValid'           => true,
                'scheme'            => $scheme,
                'userInfo'          => '',
                'host'              => self::$hostNormalized,
                'port'              => 0,
                'valueNormalized'   => self::$hostNormalized,
            ];
            $result[] = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.
                    self::$userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                    UriGeneralDelimiters::PORT_DELIMITER.$port,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
            $result[] = [
                'value'             =>
                    $scheme.UriGeneralDelimiters::SCHEME_DELIMITER.
                    UriGeneralDelimiters::AUTHORITY_DELIMITER.
                    UriGeneralDelimiters::PORT_DELIMITER.$port,
                'isValid'           => false,
                'scheme'            => '',
                'userInfo'          => '',
                'host'              => '',
                'port'              => 0,
                'valueNormalized'   => '',
            ];
        }

        return $result;
    }
}