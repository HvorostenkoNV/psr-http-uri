<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};
use HNV\Http\Uri\Collection\SchemeAllowedCharacters;

use function str_replace;
use function strtolower;
/** ***********************************************************************************************
 * URI scheme normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Scheme implements NormalizerInterface
{
    use RegularExpressionCheckerTrait;

    private const MASK_PATTERN = '/^[a-z]{1}[a-z0-9#SPECIAL_CHARS#]{1,}$/';
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value): string
    {
        $valueString    = (string) $value;
        $valueLowercase = strtolower($valueString);

        if (!self::checkRegularExpressionMatch($valueLowercase)) {
            throw new NormalizingException(
                "scheme \"$valueLowercase\" is invalid"
            );
        }

        return $valueLowercase;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    protected static function buildRegularExpressionMask(): string
    {
        $specialCharsMask = '';

        foreach (SchemeAllowedCharacters::get() as $char) {
            $specialCharsMask .= "\\$char";
        }

        return str_replace('#SPECIAL_CHARS#', $specialCharsMask, self::MASK_PATTERN);
    }
}
