<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\Host;

use HNV\Http\Uri\Collection\IpAddressV6Rules;
use HNV\Http\UriTests\Generator\{
    DomainName\InvalidValuesGenerator  as DomainNameInvalidValues,
    InvalidValuesGeneratorInterface,
    IpAddressV4\InvalidValuesGenerator as IpAddressV4InvalidValues,
    IpAddressV6\InvalidValuesGenerator as IpAddressV6InvalidValues,
};
use HNV\Http\UriTests\ValueObject\InvalidValue;

class InvalidValuesGenerator implements InvalidValuesGeneratorInterface
{
    public function generate(): iterable
    {
        yield from (new DomainNameInvalidValues())->generate();
        yield from (new IpAddressV4InvalidValues())->generate();

        foreach ((new IpAddressV6InvalidValues())->generate() as $value) {
            yield new InvalidValue(
                IpAddressV6Rules::LEFT_FRAME->value
                .$value->value
                .IpAddressV6Rules::RIGHT_FRAME->value
            );
        }
    }
}
