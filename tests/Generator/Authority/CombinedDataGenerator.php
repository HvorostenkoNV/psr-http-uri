<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Authority;

use HNV\Http\Uri\Collection\{
    PortRules,
    SchemeRules,
    UserInfoRules,
};
use HNV\Http\UriTests\Generator\{
    AbstractCombinationsGenerator,
    Host\InvalidValuesGenerator    as HostInvalidValuesGenerator,
    Host\NormalizedValuesGenerator as HostNormalizedValuesGenerator,
    Port\InvalidValuesGenerator    as PortInvalidValuesGenerator,
    Port\NormalizedValuesGenerator as PortNormalizedValuesGenerator,
    UserInfo\CombinedDataGenerator as UserInfoCombinedDataGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    Authority\CombinedData as AuthorityCombinedData,
    UserInfo\CombinedData  as UserInfoCombinedData,
    UserInfo\Data          as UserInfoData,
};

use function strlen;

class CombinedDataGenerator extends AbstractCombinationsGenerator
{
    /** @var UserInfoCombinedData[] */
    private array $userInfoValidCombinations   = [];

    /** @var UserInfoCombinedData[] */
    private array $userInfoInvalidCombinations = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new UserInfoCombinedDataGenerator())->generate() as $combination) {
            strlen($combination->fullValue) > 0
                ? $this->userInfoValidCombinations[]   = $combination
                : $this->userInfoInvalidCombinations[] = $combination;
        }
    }

    /**
     * @return AuthorityCombinedData[]
     */
    public function generate(): iterable
    {
        yield from $this->getUserInfoCombinations();
        yield from $this->getHostCombinations();
        yield from $this->getPortCombinations();
        yield from $this->getSchemeWithPortCombinations();
    }

    private function getUserInfoCombinations(): iterable
    {
        $userInfoDelimiter = UserInfoRules::URI_DELIMITER->value;
        $portDelimiter     = PortRules::URI_DELIMITER->value;

        foreach ($this->userInfoValidCombinations as $combination) {
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($combination->login, $combination->password),
                $this->host,
                $this->port,
                $combination->fullValue.$userInfoDelimiter
                .$this->hostNormalized.$portDelimiter.$this->portNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($combination->login, $combination->password),
                '',
                $this->port,
                '',
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($combination->login, $combination->password),
                $this->host,
                0,
                $combination->fullValue.$userInfoDelimiter.$this->hostNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($combination->login, $combination->password),
                '',
                0,
                '',
            );
        }

        foreach ($this->userInfoInvalidCombinations as $combination) {
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($combination->login, $combination->password),
                $this->host,
                $this->port,
                $this->hostNormalized.$portDelimiter.$this->portNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($combination->login, $combination->password),
                '',
                $this->port,
                '',
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($combination->login, $combination->password),
                $this->host,
                0,
                $this->hostNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($combination->login, $combination->password),
                '',
                0,
                '',
            );
        }
    }

    private function getHostCombinations(): iterable
    {
        $userInfoDelimiter = UserInfoRules::URI_DELIMITER->value;
        $portDelimiter     = PortRules::URI_DELIMITER->value;

        foreach ((new HostNormalizedValuesGenerator())->generate() as $value) {
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($this->login, $this->password),
                $value->value,
                $this->port,
                $this->userInfoNormalized.$userInfoDelimiter
                .$value->valueNormalized.$portDelimiter.$this->portNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData('', ''),
                $value->value,
                $this->port,
                $value->valueNormalized.$portDelimiter.$this->portNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($this->login, $this->password),
                $value->value,
                0,
                $this->userInfoNormalized.$userInfoDelimiter.$value->valueNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData('', ''),
                $value->value,
                0,
                $value->valueNormalized,
            );
        }

        foreach ((new HostInvalidValuesGenerator())->generate() as $value) {
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($this->login, $this->password),
                $value->value,
                $this->port,
                '',
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData('', ''),
                $value->value,
                $this->port,
                '',
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($this->login, $this->password),
                $value->value,
                0,
                '',
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData('', ''),
                $value->value,
                0,
                '',
            );
        }
    }

    private function getPortCombinations(): iterable
    {
        $userInfoDelimiter = UserInfoRules::URI_DELIMITER->value;
        $portDelimiter     = PortRules::URI_DELIMITER->value;

        foreach ((new PortNormalizedValuesGenerator())->generate() as $value) {
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($this->login, $this->password),
                $this->host,
                $value->value,
                $this->userInfoNormalized.$userInfoDelimiter
                .$this->hostNormalized.$portDelimiter.$value->valueNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData('', ''),
                $this->host,
                $value->value,
                $this->hostNormalized.$portDelimiter.$value->valueNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($this->login, $this->password),
                '',
                $value->value,
                '',
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData('', ''),
                '',
                $value->value,
                '',
            );
        }

        foreach ((new PortInvalidValuesGenerator())->generate() as $value) {
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($this->login, $this->password),
                $this->host,
                $value->value,
                $this->userInfoNormalized.$userInfoDelimiter.$this->hostNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData('', ''),
                $this->host,
                $value->value,
                $this->hostNormalized,
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData($this->login, $this->password),
                '',
                $value->value,
                '',
            );
            yield new AuthorityCombinedData(
                '',
                new UserInfoData('', ''),
                '',
                $value->value,
                '',
            );
        }
    }

    private function getSchemeWithPortCombinations(): iterable
    {
        $userInfoDelimiter = UserInfoRules::URI_DELIMITER->value;

        foreach (SchemeRules::STANDARD_PORTS as $scheme => $ports) {
            foreach ($ports as $port) {
                yield new AuthorityCombinedData(
                    $scheme,
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $port,
                    $this->userInfoNormalized.$userInfoDelimiter.$this->hostNormalized,
                );
                yield new AuthorityCombinedData(
                    $scheme,
                    new UserInfoData('', ''),
                    $this->host,
                    $port,
                    $this->hostNormalized,
                );
                yield new AuthorityCombinedData(
                    $scheme,
                    new UserInfoData($this->login, $this->password),
                    '',
                    $port,
                    '',
                );
                yield new AuthorityCombinedData(
                    $scheme,
                    new UserInfoData('', ''),
                    '',
                    $port,
                    '',
                );
            }
        }
    }
}
