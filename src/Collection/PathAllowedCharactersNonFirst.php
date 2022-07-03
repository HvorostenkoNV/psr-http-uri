<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

/**
 * URI path allowed character`s (NON-first/leading) collection.
 *
 * Path can not start with such characters, but they are allowed inside.
 */
enum PathAllowedCharactersNonFirst: string
{
    case COLON = ':';
}
