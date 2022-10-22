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
    public const LEFT_FRAME             = SpecialCharacters::OPEN_BRACKET;
    public const RIGHT_FRAME            = SpecialCharacters::CLOSE_BRACKET;
    public const SEGMENT_MAX_LENGTH     = 4;

    public static function mask(): string
    {
        $lettersLowercase   = 'a-f';
        $lettersUppercase   = 'A-F';
        $digits             = '0-9';
        $maxLength          = self::SEGMENT_MAX_LENGTH;

        return "/^[{$digits}{$lettersLowercase}{$lettersUppercase}]{1,{$maxLength}}$/";
    }
}
