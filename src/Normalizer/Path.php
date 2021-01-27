<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException
};
use HNV\Http\Uri\Collection\{
    UriSubDelimiters,
    PathAllowedCharacters
};

use function strlen;
use function str_replace;
use function implode;
use function explode;
use function rawurlencode;
use function rawurldecode;
/** ***********************************************************************************************
 * URI path normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Path implements NormalizerInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value): string
    {
        $valueString        = (string) $value;
        $valueExploded      = explode(UriSubDelimiters::PATH_PARTS_SEPARATOR, $valueString);
        $invalidFirstChars  = PathAllowedCharacters::NON_FIRST_CHARS;
        $result             = [];

        foreach ($invalidFirstChars as $char) {
            if ($valueString[0] === $char) {
                throw new NormalizingException(
                    "path \"$valueString\" can not begin with character \"$char\""
                );
            }
        }

        foreach ($valueExploded as $part) {
            try {
                $result[] = strlen($part) > 0 ? self::normalizePart($part) : '';
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "path part \"$part\" validation failed",
                    0,
                    $exception
                );
            }
        }

        return implode(UriSubDelimiters::PATH_PARTS_SEPARATOR, $result);
    }
    /** **********************************************************************
     * Normalize path part value.
     *
     * @param   string $value               Path part.
     *
     * @return  string                      Normalized path part.
     * @throws  NormalizingException        Normalizing error.
     ************************************************************************/
    private static function normalizePart(string $value): string
    {
        if (strlen($value) === 0) {
            throw new NormalizingException('value is empty string');
        }

        $result = rawurlencode(rawurldecode($value));

        foreach (PathAllowedCharacters::get() as $char) {
            $charEncoded    = rawurlencode($char);
            $result         = str_replace($charEncoded, $char, $result);
        }

        return $result;
    }
}