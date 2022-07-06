<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\CasesValuesProviderTrait;

/**
 * URI query allowed character`s collection.
 */
enum QueryAllowedCharacters: string
{
    use CasesValuesProviderTrait;

    case ASTERISK   = '*';
    case MINUS      = '-';
    case DOT        = '.';
    case UNDERSCORE = '_';
}
