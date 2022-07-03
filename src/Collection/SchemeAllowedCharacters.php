<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

/**
 * URI scheme allowed character`s collection.
 */
enum SchemeAllowedCharacters: string
{
    case PLUS   = '+';
    case MINUS  = '-';
    case DOT    = '.';
}
