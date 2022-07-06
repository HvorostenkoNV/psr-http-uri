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
     */
    protected static function checkRegularExpressionMatch(string $value): bool
    {
        if (!self::$regularExpressionMask) {
            self::$regularExpressionMask = self::buildRegularExpressionMask();
        }

        $matches = [];
        preg_match(self::$regularExpressionMask, $value, $matches);

        return isset($matches[0]) && $matches[0] === $value;
    }

    /**
     * Build mask for regular expression checking.
     */
    abstract protected static function buildRegularExpressionMask(): string;
}
