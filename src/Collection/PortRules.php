<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

class PortRules
{
    public const URI_DELIMITER = SpecialCharacters::COLON;
    public const MIN_VALUE     = 0;
    public const MAX_VALUE     = 65535;
}
