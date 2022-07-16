<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

use function array_merge;

class PathRules
{
    public const PARTS_SEPARATOR              = SpecialCharacters::FORWARD_SLASH;
    public const ALLOWED_CHARACTERS_NON_FIRST = [
        SpecialCharacters::COLON,
    ];
    public const ALLOWED_CHARACTERS_OTHERS    = [
        SpecialCharacters::PLUS,
        SpecialCharacters::MINUS,
        SpecialCharacters::DOT,
        SpecialCharacters::ASTERISK,
        SpecialCharacters::UNDERSCORE,
        SpecialCharacters::TILDE,
        SpecialCharacters::EXCLAMATION_POINT,
        SpecialCharacters::DOLLAR,
        SpecialCharacters::AMPERSAND,
        SpecialCharacters::APOSTROPHE,
        SpecialCharacters::OPEN_PARENTHESIS,
        SpecialCharacters::CLOSE_PARENTHESIS,
        SpecialCharacters::COMMA,
        SpecialCharacters::SEMICOLON,
        SpecialCharacters::EQUAL,
        SpecialCharacters::AMPERSAT,
    ];

    /**
     * @return SpecialCharacters[]
     */
    public static function allowedCharacters(): array
    {
        return array_merge(
            static::ALLOWED_CHARACTERS_NON_FIRST,
            static::ALLOWED_CHARACTERS_OTHERS
        );
    }
}
