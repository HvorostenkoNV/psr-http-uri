<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\PathRules;

use function explode;
use function implode;
use function rawurldecode;
use function rawurlencode;
use function str_replace;
use function strlen;

class Path implements NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function normalize($value): string
    {
        $valueString       = (string) $value;
        $valueExploded     = explode(PathRules::PARTS_SEPARATOR->value, $valueString);
        $result            = [];

        foreach (PathRules::ALLOWED_CHARACTERS_NON_FIRST as $char) {
            if ($valueString[0] === $char->value) {
                throw new NormalizingException(
                    "path [{$valueString}] can not begin with character [{$char->value}]"
                );
            }
        }

        foreach ($valueExploded as $part) {
            try {
                $result[] = strlen($part) > 0 ? self::normalizePart($part) : '';
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "path part [{$part}] validation failed",
                    0,
                    $exception
                );
            }
        }

        return implode(PathRules::PARTS_SEPARATOR->value, $result);
    }

    /**
     * @throws NormalizingException
     */
    private static function normalizePart(string $value): string
    {
        if (strlen($value) === 0) {
            throw new NormalizingException('value is empty string');
        }

        $result              = rawurlencode(rawurldecode($value));
        $allowedChars        = [];
        $allowedCharsEncoded = [];

        foreach (PathRules::allowedCharacters() as $character) {
            $allowedChars[]        = $character->value;
            $allowedCharsEncoded[] = rawurlencode($character->value);
        }

        return str_replace($allowedCharsEncoded, $allowedChars, $result);
    }
}
