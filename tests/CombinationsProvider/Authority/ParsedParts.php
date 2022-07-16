<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider\Authority;

use HNV\Http\Uri\Collection\{
    AuthorityRules,
    PortRules,
    SchemeRules,
    UriDelimiters,
    UserInfoRules,
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

                foreach (UriDelimiters::generalDelimiters() as $case) {
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
     */
    private static function getUserInfoCombinations(): array
    {
        $userInfoDelimiter = UserInfoRules::URI_DELIMITER->value;
        $portDelimiter     = PortRules::URI_DELIMITER->value;
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
     */
    private static function getHostCombinations(): array
    {
        $userInfoDelimiter = UserInfoRules::URI_DELIMITER->value;
        $portDelimiter     = PortRules::URI_DELIMITER->value;
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
     */
    private static function getPortCombinations(): array
    {
        $userInfoDelimiter = UserInfoRules::URI_DELIMITER->value;
        $portDelimiter     = PortRules::URI_DELIMITER->value;
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
     */
    private static function getSchemeWithPortCombinations(): array
    {
        $schemeInfoDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityInfoDelimiter = AuthorityRules::URI_DELIMITER;
        $userInfoDelimiter      = UserInfoRules::URI_DELIMITER->value;
        $portDelimiter          = PortRules::URI_DELIMITER->value;
        $result                 = [];

        foreach (SchemeRules::STANDARD_PORTS as $scheme => $ports) {
            foreach ($ports as $port) {
                $result[] = [
                    'value' => $scheme.$schemeInfoDelimiter.$authorityInfoDelimiter.
                        self::$userInfo.$userInfoDelimiter.self::$host.$portDelimiter.$port,
                    'isValid'         => true,
                    'scheme'          => $scheme,
                    'userInfo'        => self::$userInfoNormalized,
                    'host'            => self::$hostNormalized,
                    'port'            => 0,
                    'valueNormalized' => self::$userInfoNormalized.$userInfoDelimiter.
                        self::$hostNormalized,
                ];
                $result[] = [
                    'value' => $scheme.$schemeInfoDelimiter.$authorityInfoDelimiter.
                        self::$host.$portDelimiter.$port,
                    'isValid'         => true,
                    'scheme'          => $scheme,
                    'userInfo'        => '',
                    'host'            => self::$hostNormalized,
                    'port'            => 0,
                    'valueNormalized' => self::$hostNormalized,
                ];
                $result[] = [
                    'value' => $scheme.$schemeInfoDelimiter.$authorityInfoDelimiter.
                        self::$userInfo.$userInfoDelimiter.$portDelimiter.$port,
                    'isValid'         => false,
                    'scheme'          => '',
                    'userInfo'        => '',
                    'host'            => '',
                    'port'            => 0,
                    'valueNormalized' => '',
                ];
                $result[] = [
                    'value' => $scheme.$schemeInfoDelimiter.
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
