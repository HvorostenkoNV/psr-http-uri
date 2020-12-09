<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\DomainName;

use HNV\Http\Uri\Collection\DomainNameAllowedCharacters;
use HNV\Http\Uri\Normalizer\{
    NormalizingException,
    NormalizerInterface
};

use function strlen;
use function strtolower;
use function str_replace;
use function preg_match;
/** ***********************************************************************************************
 * Sub level domain normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class SubLevelDomain implements NormalizerInterface
{
    public const MAX_LENGTH = 63;

    private const MASK_PATTERN = '/^([a-z0-9]{1}[a-z0-9#SPECIAL_CHARS#]{0,}[a-z0-9]{1})|([a-z0-9]{1})$/';

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
        $maxLength      = self::MAX_LENGTH;

        if (strlen($valueLowercase) > $maxLength) {
            throw new NormalizingException(
                "sub domain \"$valueString\" is longer than $maxLength"
            );
        }

        preg_match($mask, $valueLowercase, $matches);
        if (!isset($matches[0]) || $matches[0] !== $valueLowercase) {
            throw new NormalizingException(
                "sub domain \"$valueString\" does not matched the pattern $mask"
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

            foreach (DomainNameAllowedCharacters::get() as $char) {
                $specialCharsMask .= "\\$char";
            }

            self::$mask = str_replace('#SPECIAL_CHARS#', $specialCharsMask, self::MASK_PATTERN);
        }

        return self::$mask;
    }
}