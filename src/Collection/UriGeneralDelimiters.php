<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

/**
 * URI general delimiters collection.
 */
enum UriGeneralDelimiters: string
{
    case SCHEME_OR_PORT_DELIMITER        = ':';
    case AUTHORITY_DELIMITER             = '//';
    case AUTHORITY_DELIMITER_SINGLE_CHAR = '/';
    case USER_INFO_DELIMITER             = '@';
    case IP_ADDRESS_V6_LEFT_FRAME        = '[';
    case IP_ADDRESS_V6_RIGHT_FRAME       = ']';
    case QUERY_DELIMITER                 = '?';
    case FRAGMENT_DELIMITER              = '#';
}
