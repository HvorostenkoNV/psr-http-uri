<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

use HNV\Http\Uri\Collection\IpAddressV6Rules;
use HNV\Http\UriTests\ValuesProvider\IpAddress\{
    V4  as IpAddressV4ValuesProvider,
    V6  as IpAddressV6ValuesProvider,
};

use function array_merge;

/**
 * URI host values provider.
 */
class Host implements ValuesProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getValidValues(): array
    {
        $leftBracer          = IpAddressV6Rules::LEFT_FRAME->value;
        $rightBracer         = IpAddressV6Rules::RIGHT_FRAME->value;
        $ipAddressesV6Values = [];

        foreach (IpAddressV6ValuesProvider::getValidValues() as $value => $valueNormalized) {
            $ipAddressesV6Values[$leftBracer.$value.$rightBracer] = $leftBracer
                .$valueNormalized.$rightBracer;
        }

        return array_merge(
            DomainName::getValidValues(),
            IpAddressV4ValuesProvider::getValidValues(),
            $ipAddressesV6Values,
        );
    }

    /**
     * {@inheritDoc}
     */
    public static function getInvalidValues(): array
    {
        $leftBracer          = IpAddressV6Rules::LEFT_FRAME->value;
        $rightBracer         = IpAddressV6Rules::RIGHT_FRAME->value;
        $ipAddressesV6Values = [];

        foreach (IpAddressV6ValuesProvider::getInvalidValues() as $invalidValue) {
            $ipAddressesV6Values[] = $leftBracer.$invalidValue.$rightBracer;
        }

        return array_merge(
            DomainName::getInvalidValues(),
            IpAddressV4ValuesProvider::getInvalidValues(),
            $ipAddressesV6Values,
        );
    }
}
