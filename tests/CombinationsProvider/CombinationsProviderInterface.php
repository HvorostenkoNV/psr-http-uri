<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\CombinationsProvider;

/**
 * URI different combinations provider interface.
 */
interface CombinationsProviderInterface
{
    /**
     * Get available combinations as set of data.
     *
     * @return array[] set of arrays,
     *                 where each array describes in example tag
     */
    public static function get(): array;
}
