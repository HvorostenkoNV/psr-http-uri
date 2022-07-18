<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Authority;

use HNV\Http\Uri\Collection\{
    AuthorityRules,
    PortRules,
    SchemeRules,
    UriDelimiters,
    UserInfoRules,
};
use HNV\Http\UriTests\Generator\{
    AbstractCombinationsGenerator,
    Host\InvalidValuesGenerator    as HostInvalidValuesGenerator,
    Host\NormalizedValuesGenerator as HostNormalizedValuesGenerator,
    Port\InvalidValuesGenerator    as PortInvalidValuesGenerator,
    Port\NormalizedValuesGenerator as PortNormalizedValuesGenerator,
    UserInfo\ParsedDataGenerator   as UserInfoParsedDataGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    Authority\ParsedData as AuthorityParsedData,
    UserInfo\ParsedData  as UserInfoParsedData,
};

class ParsedDataGenerator extends AbstractCombinationsGenerator
{
    /** @var UserInfoParsedData[] */
    private array $userInfoValidCombinations    = [];

    /** @var UserInfoParsedData[] */
    private array $userInfoInvalidCombinations  = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new UserInfoParsedDataGenerator())->generate() as $combination) {
            if ($combination->isValid) {
                $valueToParse = $combination->valueToParse;

                foreach (UriDelimiters::generalDelimiters() as $case) {
                    if (str_contains($combination->valueToParse, $case->value)) {
                        $valueToParse = $combination->valueNormalized;
                        break;
                    }
                }

                $this->userInfoValidCombinations[] = new UserInfoParsedData(
                    $valueToParse,
                    true,
                    $combination->valueNormalized
                );
            } else {
                $this->userInfoInvalidCombinations[] = $combination;
            }
        }
    }

    /**
     * @return AuthorityParsedData[]
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
            yield new AuthorityParsedData(
                $combination->valueToParse.$userInfoDelimiter
                .$this->host.$portDelimiter.$this->port,
                true,
                '',
                $combination->valueNormalized,
                $this->hostNormalized,
                $this->portNormalized,
                $combination->valueNormalized.$userInfoDelimiter
                .$this->hostNormalized.$portDelimiter.$this->portNormalized,
            );
            yield new AuthorityParsedData(
                $combination->valueToParse.$userInfoDelimiter.$portDelimiter.$this->port,
                false,
            );
            yield new AuthorityParsedData(
                $combination->valueToParse.$userInfoDelimiter.$this->host,
                true,
                '',
                $combination->valueNormalized,
                $this->hostNormalized,
                0,
                $combination->valueNormalized.$userInfoDelimiter.$this->hostNormalized,
            );
            yield new AuthorityParsedData(
                $combination->valueToParse.$userInfoDelimiter,
                false,
            );
        }

        foreach ($this->userInfoInvalidCombinations as $combination) {
            yield new AuthorityParsedData(
                $combination->valueToParse.$userInfoDelimiter
                .$this->host.$portDelimiter.$this->port,
                false,
            );
            yield new AuthorityParsedData(
                $combination->valueToParse.$userInfoDelimiter.$portDelimiter.$this->port,
                false,
            );
            yield new AuthorityParsedData(
                $combination->valueToParse.$userInfoDelimiter.$this->host,
                false,
            );
            yield new AuthorityParsedData(
                $combination->valueToParse.$userInfoDelimiter,
                false,
            );
        }
    }

    private function getHostCombinations(): iterable
    {
        $userInfoDelimiter = UserInfoRules::URI_DELIMITER->value;
        $portDelimiter     = PortRules::URI_DELIMITER->value;

        foreach ((new HostNormalizedValuesGenerator())->generate() as $value) {
            yield new AuthorityParsedData(
                $this->userInfo.$userInfoDelimiter.$value->value.$portDelimiter.$this->port,
                true,
                '',
                $this->userInfoNormalized,
                $value->valueNormalized,
                $this->portNormalized,
                $this->userInfoNormalized.$userInfoDelimiter
                .$value->valueNormalized.$portDelimiter.$this->portNormalized,
            );
            yield new AuthorityParsedData(
                $value->value.$portDelimiter.$this->port,
                true,
                '',
                '',
                $value->valueNormalized,
                $this->portNormalized,
                $value->valueNormalized.$portDelimiter.$this->portNormalized,
            );
            yield new AuthorityParsedData(
                $this->userInfo.$userInfoDelimiter.$value->value,
                true,
                '',
                $this->userInfoNormalized,
                $value->valueNormalized,
                0,
                $this->userInfoNormalized.$userInfoDelimiter.$value->valueNormalized,
            );
            yield new AuthorityParsedData(
                $value->value,
                true,
                '',
                '',
                $value->valueNormalized,
                0,
                $value->valueNormalized,
            );
        }

        foreach ((new HostInvalidValuesGenerator())->generate() as $value) {
            yield new AuthorityParsedData(
                $this->userInfo.$userInfoDelimiter.$value->value.$portDelimiter.$this->port,
                false,
            );
            yield new AuthorityParsedData(
                $value->value.$portDelimiter.$this->port,
                false,
            );
            yield new AuthorityParsedData(
                $this->userInfo.$userInfoDelimiter.$value->value,
                false,
            );
            yield new AuthorityParsedData(
                $value->value,
                false,
            );
        }
    }

    private function getPortCombinations(): iterable
    {
        $userInfoDelimiter = UserInfoRules::URI_DELIMITER->value;
        $portDelimiter     = PortRules::URI_DELIMITER->value;

        foreach ((new PortNormalizedValuesGenerator())->generate() as $value) {
            yield new AuthorityParsedData(
                $this->userInfo.$userInfoDelimiter.$this->host.$portDelimiter.$value->value,
                true,
                '',
                $this->userInfoNormalized,
                $this->hostNormalized,
                $value->valueNormalized,
                $this->userInfoNormalized.$userInfoDelimiter
                .$this->hostNormalized.$portDelimiter.$value->valueNormalized,
            );
            yield new AuthorityParsedData(
                $this->host.$portDelimiter.$value->value,
                true,
                '',
                '',
                $this->hostNormalized,
                $value->valueNormalized,
                $this->hostNormalized.$portDelimiter.$value->valueNormalized,
            );
            yield new AuthorityParsedData(
                $this->userInfo.$userInfoDelimiter.$portDelimiter.$value->value,
                false,
            );
            yield new AuthorityParsedData(
                $portDelimiter.$value->value,
                false,
            );
        }

        foreach ((new PortInvalidValuesGenerator())->generate() as $value) {
            yield new AuthorityParsedData(
                $this->userInfo.$userInfoDelimiter.$this->host.$portDelimiter.$value->value,
                false,
            );
            yield new AuthorityParsedData(
                $this->host.$portDelimiter.$value->value,
                false,
            );
            yield new AuthorityParsedData(
                $this->userInfo.$userInfoDelimiter.$portDelimiter.$value->value,
                false,
            );
            yield new AuthorityParsedData(
                $portDelimiter.$value->value,
                false,
            );
        }
    }

    private function getSchemeWithPortCombinations(): iterable
    {
        $schemeInfoDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityInfoDelimiter = AuthorityRules::URI_DELIMITER;
        $userInfoDelimiter      = UserInfoRules::URI_DELIMITER->value;
        $portDelimiter          = PortRules::URI_DELIMITER->value;

        foreach (SchemeRules::STANDARD_PORTS as $scheme => $ports) {
            foreach ($ports as $port) {
                yield new AuthorityParsedData(
                    $scheme.$schemeInfoDelimiter.$authorityInfoDelimiter.
                    $this->userInfo.$userInfoDelimiter.$this->host.$portDelimiter.$port,
                    true,
                    $scheme,
                    $this->userInfoNormalized,
                    $this->hostNormalized,
                    0,
                    $this->userInfoNormalized.$userInfoDelimiter.$this->hostNormalized,
                );
                yield new AuthorityParsedData(
                    $scheme.$schemeInfoDelimiter.$authorityInfoDelimiter
                    .$this->host.$portDelimiter.$port,
                    true,
                    $scheme,
                    '',
                    $this->hostNormalized,
                    0,
                    $this->hostNormalized,
                );
                yield new AuthorityParsedData(
                    $scheme.$schemeInfoDelimiter.$authorityInfoDelimiter
                    .$this->userInfo.$userInfoDelimiter.$portDelimiter.$port,
                    false,
                );
                yield new AuthorityParsedData(
                    $scheme.$schemeInfoDelimiter.$authorityInfoDelimiter.$portDelimiter.$port,
                    false,
                );
            }
        }
    }
}
