<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

class DomainNameRules
{
    public const LEVELS_DELIMITER                       = SpecialCharacters::DOT;
    public const TOP_LEVEL_MIN_LENGTH                   = 2;
    public const TOP_LEVEL_MAX_LENGTH                   = 6;
    public const SUB_LEVEL_MAX_LENGTH                   = 63;
    public const SUB_LEVEL_ALLOWED_SPECIAL_CHARACTERS   = [
        SpecialCharacters::MINUS,
    ];

    public static function topLevelMask(): string
    {
        $letter     = 'a-z';
        $minLength  = self::TOP_LEVEL_MIN_LENGTH;
        $maxLength  = self::TOP_LEVEL_MAX_LENGTH;

        return "/^[{$letter}]{{$minLength},{$maxLength}}$/";
    }

    /**
     * @return string[]
     */
    public static function subLevelMasks(): array
    {
        $maxLength          = self::SUB_LEVEL_MAX_LENGTH;
        $specialCharacters  = '';

        foreach (static::SUB_LEVEL_ALLOWED_SPECIAL_CHARACTERS as $case) {
            $specialCharacters .= "\\{$case->value}";
        }

        $letterOrDigit      = 'a-z0-9';
        $oneLetterOrDigit   = "[{$letterOrDigit}]{1}";
        $anyAllowedChar     = "[{$letterOrDigit}{$specialCharacters}]{0,}";

        return [
            "/^({$oneLetterOrDigit}{$anyAllowedChar}{$oneLetterOrDigit})|({$oneLetterOrDigit})$/",
            "/^.{1,{$maxLength}}$/",
        ];
    }
}
