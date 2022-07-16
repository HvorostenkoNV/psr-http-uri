<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

use function str_replace;

class DomainNameRules
{
    public const LEVELS_DELIMITER               = SpecialCharacters::DOT;
    public const TOP_LEVEL_MIN_LENGTH           = 2;
    public const TOP_LEVEL_MAX_LENGTH           = 6;
    public const SUB_LEVEL_MAX_LENGTH           = 63;
    public const SUB_LEVEL_ALLOWED_CHARACTERS   = [
        SpecialCharacters::MINUS,
    ];
    private const TOP_LEVEL_MASK                = '/^[a-z]{1,}$/';
    private const SUB_LEVEL_MASK                =
        '/^([a-z0-9]{1}[a-z0-9#SPECIAL_CHARS#]{0,}[a-z0-9]{1})|([a-z0-9]{1})$/';

    private static ?string $subLevelMaskReady = null;

    public static function topLevelMask(): string
    {
        return static::TOP_LEVEL_MASK;
    }

    public static function subLevelMask(): string
    {
        if (!self::$subLevelMaskReady) {
            $specialCharacters = '';

            foreach (static::SUB_LEVEL_ALLOWED_CHARACTERS as $case) {
                $specialCharacters .= "\\{$case->value}";
            }

            self::$subLevelMaskReady = str_replace(
                '#SPECIAL_CHARS#',
                $specialCharacters,
                static::SUB_LEVEL_MASK
            );
        }

        return self::$subLevelMaskReady;
    }
}
