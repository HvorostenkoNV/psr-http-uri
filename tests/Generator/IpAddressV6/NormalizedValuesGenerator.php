<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\IpAddressV6;

use HNV\Http\UriTests\{
    Generator\InvalidValuesGeneratorInterface,
    ValueObject\NormalizedValue,
};

class NormalizedValuesGenerator implements InvalidValuesGeneratorInterface
{
    public function generate(): iterable
    {
        yield new NormalizedValue(
            '1234:5678:1357:2468:aabb:ccdd:eeff:ABCD',
            '1234:5678:1357:2468:aabb:ccdd:eeff:ABCD'
        );
        yield new NormalizedValue(
            '1234:123:12:1:abcd:ABCD:AbCd:FF',
            '1234:123:12:1:abcd:ABCD:AbCd:FF'
        );
        yield new NormalizedValue('1a:2b:3c:4d:5e:6f:7:8', '1a:2b:3c:4d:5e:6f:7:8');

        yield new NormalizedValue('1:2:3:4:5:6:7:8', '1:2:3:4:5:6:7:8');
        yield new NormalizedValue('01:2:3:4:5:6:7:8', '1:2:3:4:5:6:7:8');
        yield new NormalizedValue('001:2:3:4:5:6:7:8', '1:2:3:4:5:6:7:8');
        yield new NormalizedValue('010:2:3:4:5:6:7:8', '10:2:3:4:5:6:7:8');

        yield new NormalizedValue('1:2:3:4:5:6:0:0', '1:2:3:4:5:6::');
        yield new NormalizedValue('0:0:3:4:5:6:7:8', '::3:4:5:6:7:8');
        yield new NormalizedValue('1:2:3:0:0:6:7:8', '1:2:3::6:7:8');

        yield new NormalizedValue('1:0:00:4:000:0000:0:8', '1:0:0:4::8');
        yield new NormalizedValue('1:0:0:4:5:0:0:8', '1::4:5:0:0:8');
        yield new NormalizedValue('0:00:000:0000:0:00:000:0000', '::');

        yield new NormalizedValue('1:2::', '1:2::');
        yield new NormalizedValue('::7:8', '::7:8');
        yield new NormalizedValue('1:2::7:8', '1:2::7:8');
        yield new NormalizedValue('::', '::');

        yield new NormalizedValue('1:2:3:4:5:6:1.0.0.1', '1:2:3:4:5:6:1.0.0.1');
        yield new NormalizedValue('1:2:3:4::1.0.0.1', '1:2:3:4::1.0.0.1');
        yield new NormalizedValue('1:2:3:4:0:0:1.0.0.1', '1:2:3:4::1.0.0.1');

        yield new NormalizedValue('::3:4:5:6:1.0.0.1', '::3:4:5:6:1.0.0.1');
        yield new NormalizedValue('1:2::5:6:1.0.0.1', '1:2::5:6:1.0.0.1');
        yield new NormalizedValue('01:002:0000:000:5:6:01.010.000.1', '1:2::5:6:1.10.0.1');
    }
}
