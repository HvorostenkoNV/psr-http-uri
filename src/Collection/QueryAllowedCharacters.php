<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

/**
 * URI query allowed character`s collection.
 */
enum QueryAllowedCharacters: string
{
    case ASTERISK   = '*';
    case MINUS      = '-';
    case DOT        = '.';
    case UNDERSCORE = '_';
}
