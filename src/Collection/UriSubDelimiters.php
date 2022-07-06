<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\CasesValuesProviderTrait;

/**
 * URI sub delimiters collection.
 */
enum UriSubDelimiters: string
{
    use CasesValuesProviderTrait;

    case USER_INFO_SEPARATOR                = ':';
    case PATH_PARTS_SEPARATOR               = '/';
    case QUERY_FIELDS_SEPARATOR             = '&';
    case QUERY_FIELD_VALUE_SEPARATOR        = '=';
    case SUB_DELIMITER_EXCLAMATION_POINT    = '!';
    case SUB_DELIMITER_DOLLAR               = '$';
    case SUB_DELIMITER_BACKSLASH            = '\'';
    case SUB_DELIMITER_OPEN_PARENTHESIS     = '(';
    case SUB_DELIMITER_CLOSE_PARENTHESIS    = ')';
    case SUB_DELIMITER_ASTERISK             = '*';
    case SUB_DELIMITER_PLUS                 = '+';
    case SUB_DELIMITER_COMMA                = ',';
    case SUB_DELIMITER_SEMICOLON            = ';';
}
