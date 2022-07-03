<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\Authority;

use HNV\Http\Uri\Collection\{
    SchemeStandardPorts,
    UriGeneralDelimiters,
};
use HNV\Http\UriTests\CombinationsProvider\UserInfo\ParsedParts as UserInfoCombinationsProvider;
use HNV\Http\UriTests\CombinationsProvider\{
    AbstractCombinationsProvider,
    CombinationsProviderInterface,
};
use HNV\Http\UriTests\ValuesProvider\{
    Host    as HostValuesProvider,
    Port    as PortValuesProvider,
};

use function array_merge;
use function str_contains;

/**
 * URI authority in parsed parts different combination`s provider.
 */
class ParsedParts extends AbstractCombinationsProvider implements CombinationsProviderInterface
{
    private static array $userInfoValidValues   = [];
    private static array $userInfoInvalidValues = [];

    /**
     * {@inheritDoc}
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
     */
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

    /**
     * {@inheritDoc}
     */
    protected static function initializeDefaultValues(): void
    {
        parent::initializeDefaultValues();

        foreach (UserInfoCombinationsProvider::get() as $combination) {
            if ($combination['isValid']) {
                $value           = $combination['value'];
                $valueNormalized = $combination['valueNormalized'];

                foreach (UriGeneralDelimiters::cases() as $case) {
                    if (str_contains($value, $case->value)) {
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

    /**
     * Get user info combinations.
     *
     * @return array combinations data
     */
    private static function getUserInfoCombinations(): array
    {
        $userInfoDelimiter = UriGeneralDelimiters::USER_INFO_DELIMITER->value;
        $portDelimiter     = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $result            = [];

        foreach (self::$userInfoValidValues as $userInfo => $userInfoNormalized) {
            $result[] = [
                'value' => $userInfo.$userInfoDelimiter.
                    self::$host.$portDelimiter.self::$port,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => $userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => self::$portNormalized,
                'valueNormalized' => $userInfoNormalized.$userInfoDelimiter.
                    self::$hostNormalized.$portDelimiter.self::$portNormalized,
            ];
            $result[] = [
                'value'           => $userInfo.$userInfoDelimiter.$portDelimiter.self::$port,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => $userInfo.$userInfoDelimiter.self::$host,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => $userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => 0,
                'valueNormalized' => $userInfoNormalized.$userInfoDelimiter.
                    self::$hostNormalized,
            ];
            $result[] = [
                'value'           => $userInfo.$userInfoDelimiter,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
        }
        foreach (self::$userInfoInvalidValues as $invalidUserInfo) {
            $result[] = [
                'value' => $invalidUserInfo.$userInfoDelimiter.self::$host.
                    $portDelimiter.self::$port,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value' => $invalidUserInfo.$userInfoDelimiter.
                    $portDelimiter.self::$port,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => $invalidUserInfo.$userInfoDelimiter.self::$host,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => $invalidUserInfo.$userInfoDelimiter,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
        }

        return $result;
    }

    /**
     * Get host combinations.
     *
     * @return array combinations data
     */
    private static function getHostCombinations(): array
    {
        $userInfoDelimiter = UriGeneralDelimiters::USER_INFO_DELIMITER->value;
        $portDelimiter     = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $result            = [];

        foreach (HostValuesProvider::getValidValues() as $host => $hostNormalized) {
            $result[] = [
                'value' => self::$userInfo.$userInfoDelimiter.$host.
                    $portDelimiter.self::$port,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => self::$userInfoNormalized,
                'host'            => $hostNormalized,
                'port'            => self::$portNormalized,
                'valueNormalized' => self::$userInfoNormalized.$userInfoDelimiter.
                    $hostNormalized.$portDelimiter.self::$portNormalized,
            ];
            $result[] = [
                'value'           => $host.$portDelimiter.self::$port,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => $hostNormalized,
                'port'            => self::$portNormalized,
                'valueNormalized' => $hostNormalized.$portDelimiter.self::$portNormalized,
            ];
            $result[] = [
                'value'           => self::$userInfo.$userInfoDelimiter.$host,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => self::$userInfoNormalized,
                'host'            => $hostNormalized,
                'port'            => 0,
                'valueNormalized' => self::$userInfoNormalized.$userInfoDelimiter.
                    $hostNormalized,
            ];
            $result[] = [
                'value'           => $host,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => $hostNormalized,
                'port'            => 0,
                'valueNormalized' => $hostNormalized,
            ];
        }
        foreach (HostValuesProvider::getInvalidValues() as $invalidHost) {
            $result[] = [
                'value' => self::$userInfo.$userInfoDelimiter.$invalidHost.
                    $portDelimiter.self::$port,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => $invalidHost.$portDelimiter.self::$port,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => self::$userInfo.$userInfoDelimiter.$invalidHost,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => $invalidHost,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
        }

        return $result;
    }

    /**
     * Get port combinations.
     *
     * @return array combinations data
     */
    private static function getPortCombinations(): array
    {
        $userInfoDelimiter = UriGeneralDelimiters::USER_INFO_DELIMITER->value;
        $portDelimiter     = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $result            = [];

        foreach (PortValuesProvider::getValidValues() as $port => $portNormalized) {
            $result[] = [
                'value' => self::$userInfo.$userInfoDelimiter.
                    self::$host.$portDelimiter.$port,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => self::$userInfoNormalized,
                'host'            => self::$hostNormalized,
                'port'            => $portNormalized,
                'valueNormalized' => self::$userInfoNormalized.$userInfoDelimiter.
                    self::$hostNormalized.$portDelimiter.$portNormalized,
            ];
            $result[] = [
                'value'           => self::$host.$portDelimiter.$port,
                'isValid'         => true,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => self::$hostNormalized,
                'port'            => $portNormalized,
                'valueNormalized' => self::$hostNormalized.$portDelimiter.$portNormalized,
            ];
            $result[] = [
                'value'           => self::$userInfo.$userInfoDelimiter.$portDelimiter.$port,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => $portDelimiter.$port,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
        }
        foreach (PortValuesProvider::getInvalidValues() as $invalidPort) {
            $result[] = [
                'value' => self::$userInfo.$userInfoDelimiter.
                    self::$host.$portDelimiter.$invalidPort,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => self::$host.$portDelimiter.$invalidPort,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => self::$userInfo.$userInfoDelimiter.$portDelimiter.$invalidPort,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
            $result[] = [
                'value'           => $portDelimiter.$invalidPort,
                'isValid'         => false,
                'scheme'          => '',
                'userInfo'        => '',
                'host'            => '',
                'port'            => 0,
                'valueNormalized' => '',
            ];
        }

        return $result;
    }

    /**
     * Get scheme with port combinations.
     *
     * @return array combinations data
     */
    private static function getSchemeWithPortCombinations(): array
    {
        $schemeInfoDelimiter    = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $authorityInfoDelimiter = UriGeneralDelimiters::AUTHORITY_DELIMITER->value;
        $userInfoDelimiter      = UriGeneralDelimiters::USER_INFO_DELIMITER->value;
        $portDelimiter          = UriGeneralDelimiters::SCHEME_OR_PORT_DELIMITER->value;
        $result                 = [];

        foreach (SchemeStandardPorts::cases() as $case) {
            foreach ($case->ports() as $port) {
                $result[] = [
                    'value' => $case->value.$schemeInfoDelimiter.$authorityInfoDelimiter.
                        self::$userInfo.$userInfoDelimiter.self::$host.$portDelimiter.$port,
                    'isValid'         => true,
                    'scheme'          => $case->value,
                    'userInfo'        => self::$userInfoNormalized,
                    'host'            => self::$hostNormalized,
                    'port'            => 0,
                    'valueNormalized' => self::$userInfoNormalized.$userInfoDelimiter.
                        self::$hostNormalized,
                ];
                $result[] = [
                    'value' => $case->value.$schemeInfoDelimiter.$authorityInfoDelimiter.
                        self::$host.$portDelimiter.$port,
                    'isValid'         => true,
                    'scheme'          => $case->value,
                    'userInfo'        => '',
                    'host'            => self::$hostNormalized,
                    'port'            => 0,
                    'valueNormalized' => self::$hostNormalized,
                ];
                $result[] = [
                    'value' => $case->value.$schemeInfoDelimiter.$authorityInfoDelimiter.
                        self::$userInfo.$userInfoDelimiter.$portDelimiter.$port,
                    'isValid'         => false,
                    'scheme'          => '',
                    'userInfo'        => '',
                    'host'            => '',
                    'port'            => 0,
                    'valueNormalized' => '',
                ];
                $result[] = [
                    'value' => $case->value.$schemeInfoDelimiter.
                        $authorityInfoDelimiter.$portDelimiter.$port,
                    'isValid'         => false,
                    'scheme'          => '',
                    'userInfo'        => '',
                    'host'            => '',
                    'port'            => 0,
                    'valueNormalized' => '',
                ];
            }
        }

        return $result;
    }
}
