<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

class UserInfoRules
{
    public const URI_DELIMITER      = SpecialCharacters::AMPERSAT;
    public const VALUES_SEPARATOR   = SpecialCharacters::COLON;
}
