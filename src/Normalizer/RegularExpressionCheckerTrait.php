<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use function preg_match;

trait RegularExpressionCheckerTrait
{
    private static ?string $regularExpressionMask = null;

    protected static function checkRegularExpressionMatch(string $value): bool
    {
        if (!self::$regularExpressionMask) {
            self::$regularExpressionMask = self::buildRegularExpressionMask();
        }

        $matches = [];
        preg_match(self::$regularExpressionMask, $value, $matches);

        return isset($matches[0]) && $matches[0] === $value;
    }

    abstract protected static function buildRegularExpressionMask(): string;
}
