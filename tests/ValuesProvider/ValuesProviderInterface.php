<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

/**
 * Values provider interface.
 */
interface ValuesProviderInterface
{
    /**
     * Get valid values with their normalized representation.
     *
     * @return array data set, where key is value and
     *               value is its normalized representation
     */
    public static function getValidValues(): array;

    /**
     * Get invalid values set.
     *
     * @return array invalid values data set
     */
    public static function getInvalidValues(): array;
}
