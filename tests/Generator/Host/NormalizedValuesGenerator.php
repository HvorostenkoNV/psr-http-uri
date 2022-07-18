<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Host;

use HNV\Http\Uri\Collection\IpAddressV6Rules;
use HNV\Http\UriTests\Generator\{
    DomainName\NormalizedValuesGenerator  as DomainNameNormalizedValuesGenerator,
    IpAddressV4\NormalizedValuesGenerator as IpAddressV4NormalizedValuesGenerator,
    IpAddressV6\NormalizedValuesGenerator as IpAddressV6NormalizedValuesGenerator,
    NormalizedValuesGeneratorInterface,
};
use HNV\Http\UriTests\ValueObject\NormalizedValue;

class NormalizedValuesGenerator implements NormalizedValuesGeneratorInterface
{
    public function generate(): iterable
    {
        $leftBracer     = IpAddressV6Rules::LEFT_FRAME->value;
        $rightBracer    = IpAddressV6Rules::RIGHT_FRAME->value;

        yield from (new DomainNameNormalizedValuesGenerator())->generate();
        yield from (new IpAddressV4NormalizedValuesGenerator())->generate();

        foreach ((new IpAddressV6NormalizedValuesGenerator())->generate() as $value) {
            yield new NormalizedValue(
                $leftBracer.$value->value.$rightBracer,
                $leftBracer.$value->valueNormalized.$rightBracer
            );
        }
    }
}
