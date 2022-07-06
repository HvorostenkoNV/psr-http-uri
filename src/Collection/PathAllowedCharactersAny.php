<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\CasesValuesProviderTrait;

/**
 * URI path allowed character`s collection.
 */
enum PathAllowedCharactersAny: string
{
    use CasesValuesProviderTrait;

    case PLUS               = '+';
    case MINUS              = '-';
    case DOT                = '.';
    case ASTERISK           = '*';
    case UNDERSCORE         = '_';
    case TILDE              = '~';
    case EXCLAMATION_POINT  = '!';
    case DOLLAR             = '$';
    case AMPERSAND          = '&';
    case APOSTROPHE         = '\'';
    case OPEN_PARENTHESIS   = '(';
    case CLOSE_PARENTHESIS  = ')';
    case COMMA              = ',';
    case SEMICOLON          = ';';
    case EQUAL              = '=';
    case AMPERSAT           = '@';
}
