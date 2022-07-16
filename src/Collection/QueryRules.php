<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

class QueryRules
{
    public const URI_DELIMITER          = SpecialCharacters::QUESTION_MARK;
    public const FIELDS_SEPARATOR       = SpecialCharacters::AMPERSAND;
    public const FIELD_VALUE_SEPARATOR  = SpecialCharacters::EQUAL;
    public const ALLOWED_CHARACTERS     = [
        SpecialCharacters::ASTERISK,
        SpecialCharacters::MINUS,
        SpecialCharacters::DOT,
        SpecialCharacters::UNDERSCORE,
    ];
}
