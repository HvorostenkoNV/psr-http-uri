<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\IpAddressV6;

use HNV\Http\UriTests\{
    Generator\InvalidValuesGeneratorInterface,
    ValueObject\InvalidValue,
};

class InvalidValuesGenerator implements InvalidValuesGeneratorInterface
{
    public function generate(): iterable
    {
        yield new InvalidValue('1:2:3:4:5:6:7:g');
        yield new InvalidValue('1:2:3:4:5:6:7:G');
        yield new InvalidValue('1:2:3:4:5:6:7:-1');

        yield new InvalidValue('1:2:3:4:5:6:7:8:9');
        yield new InvalidValue('1:2:3:4:5:6:7');

        yield new InvalidValue('00001:2:3:4:5:6:7:8');

        yield new InvalidValue('1:2:::');
        yield new InvalidValue(':::7:8');
        yield new InvalidValue('1:2:::7:8');
        yield new InvalidValue('1::5::8');
        yield new InvalidValue(':::');

        yield new InvalidValue('1:2:3:4:5:6:7::');
        yield new InvalidValue('::2:3:4:5:6:7:8');

        yield new InvalidValue('1:2:3:4:5:1.0.0.1');
        yield new InvalidValue('1:2:3:4:5:6:7:1.0.0.1');
        yield new InvalidValue('1:2:3:4:5:6:1.0.0.256');
        yield new InvalidValue('1:2:3:4:5:6:1.0.0.-1');
        yield new InvalidValue('1:2:3:4:5:6:1.0.0.0.1');

        yield new InvalidValue('1:2:3:4:::1.0.0.1');
        yield new InvalidValue('1:2:3:4:5::1.0.0.1');

        yield new InvalidValue('::2:3:4:5:6:1.0.0.1');

        yield new InvalidValue('1:2:3:4:5:6:1.0.0.1:');
        yield new InvalidValue('1:2:3:4:5:6:1.0.0.1:0');
        yield new InvalidValue('1:2:3:4:5:6:7:');
        yield new InvalidValue(':2:3:4:5:6:7:8');
        yield new InvalidValue('1:2:3::6:7:');
        yield new InvalidValue(':2:3::6:7:8');
    }
}
