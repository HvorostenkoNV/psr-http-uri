<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException
};
use HNV\Http\Uri\Collection\SchemeAllowedCharacters;

use function str_replace;
use function strtolower;
use function preg_match;
/** ***********************************************************************************************
 * URI scheme normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Scheme implements NormalizerInterface
{
    private const MASK_PATTERN = '/^[a-z]{1}[a-z0-9#SPECIAL_CHARS#]{1,}$/';

    private static ?string $mask = null;
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value): string
    {
        $valueString    = (string) $value;
        $valueLowercase = strtolower($valueString);
        $mask           = self::getMask();
        $matches        = [];

        preg_match($mask, $valueLowercase, $matches);
        if (!isset($matches[0]) || $matches[0] !== $valueLowercase) {
            throw new NormalizingException(
                "scheme \"$valueLowercase\" does not matched the pattern $mask"
            );
        }

        return $valueLowercase;
    }
    /** **********************************************************************
     * Get mask for preg match checking.
     *
     * @return  string                      Preg match mask.
     ************************************************************************/
    private static function getMask(): string
    {
        if (!self::$mask) {
            $specialCharsMask = '';

            foreach (SchemeAllowedCharacters::get() as $char) {
                $specialCharsMask .= "\\$char";
            }

            self::$mask = str_replace('#SPECIAL_CHARS#', $specialCharsMask, self::MASK_PATTERN);
        }

        return self::$mask;
    }
}