<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

class IpAddressV4Rules
{
    public const PART_MIN_VALUE  = 0;
    public const PART_MAX_VALUE  = 255;
    public const PARTS_COUNT     = 4;
    public const PARTS_DELIMITER = SpecialCharacters::DOT;
}
