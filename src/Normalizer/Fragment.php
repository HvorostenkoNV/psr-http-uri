<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\NormalizerInterface;

use function rawurldecode;

/**
 * URI fragment normalizer.
 */
class Fragment implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString = (string) $value;

        return rawurldecode($valueString);
    }
}
