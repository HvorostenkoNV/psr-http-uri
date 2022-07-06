<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\CasesValuesProviderTrait;

/**
 * URI scheme allowed character`s collection.
 */
enum SchemeAllowedCharacters: string
{
    use CasesValuesProviderTrait;

    case PLUS   = '+';
    case MINUS  = '-';
    case DOT    = '.';
}
