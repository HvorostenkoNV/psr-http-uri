<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Uri\Collection\SchemeAllowedCharacters;

use function strtolower;
use function preg_match;
/** ***********************************************************************************************
 * URI scheme normalizer.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class Scheme implements NormalizerInterface
{
    private static $mask = null;
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value)
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

            self::$mask = "/^[a-z]{1}[a-z0-9$specialCharsMask]{1,}$/";
        }

        return self::$mask;
    }
}