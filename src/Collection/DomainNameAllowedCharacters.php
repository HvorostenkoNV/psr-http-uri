<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\CasesValuesProviderTrait;

/**
 * URI domain name allowed character`s collection.
 */
enum DomainNameAllowedCharacters: string
{
    use CasesValuesProviderTrait;

    case MINUS = '-';
}
