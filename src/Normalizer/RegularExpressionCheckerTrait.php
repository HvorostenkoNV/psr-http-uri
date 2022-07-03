<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use function preg_match;

/**
 * Regular expression checker helper trait.
 */
trait RegularExpressionCheckerTrait
{
    private static ?string $regularExpressionMask = null;

    /**
     * Check if value match to regular expression mask.
     *
     * Mask builds only once. Mask building must be implemented in class-user.
     *
     * @param string $value value
     *
     * @return bool value regular expression check result
     */
    protected static function checkRegularExpressionMatch(string $value): bool
    {
        $mask    = self::getRegularExpressionMask();
        $matches = [];

        preg_match($mask, $value, $matches);

        return isset($matches[0]) && $matches[0] === $value;
    }

    /**
     * Build mask for regular expression checking.
     *
     * @return string mask
     */
    abstract protected static function buildRegularExpressionMask(): string;

    /**
     * Get mask for regular expression checking.
     *
     * @return string mask
     */
    private static function getRegularExpressionMask(): string
    {
        if (!self::$regularExpressionMask) {
            self::$regularExpressionMask = self::buildRegularExpressionMask();
        }

        return self::$regularExpressionMask;
    }
}
