<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

class IpAddressV6Rules
{
    public const PARTS_COUNT            = 8;
    public const PARTS_COUNT_WITHOUT_V4 = 6;
    public const DELIMITER              = SpecialCharacters::COLON;
    public const SHORTEN                = '::';
    public const SEGMENT_MINIMAL_VALUE  = 0;
    public const LEFT_FRAME             = SpecialCharacters::OPEN_BRACKET;
    public const RIGHT_FRAME            = SpecialCharacters::CLOSE_BRACKET;
    private const SEGMENT_MASK          = '/^[0-9a-fA-F]{1,4}$/';

    public static function segmentMask(): string
    {
        return static::SEGMENT_MASK;
    }
}
