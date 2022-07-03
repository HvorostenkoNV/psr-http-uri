<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider\UserInfo;

use HNV\Http\UriTests\ValuesProvider\ValuesProviderInterface;

/**
 * URI user password values provider.
 */
class Password implements ValuesProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getValidValues(): array
    {
        return Value::getValidValues();
    }

    /**
     * {@inheritDoc}
     */
    public static function getInvalidValues(): array
    {
        return Value::getInvalidValues();
    }
}
