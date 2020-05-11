<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\DomainName;

use HNV\Http\Uri\Normalizer\{
    NormalizingException,
    NormalizerInterface
};

use function strlen;
use function strtolower;
use function preg_match;
/** ***********************************************************************************************
 * Top level domain normalizer.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class TopLevelDomain implements NormalizerInterface
{
    public const MIN_LENGTH = 2;
    public const MAX_LENGTH = 6;
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value)
    {
        $valueString    = (string) $value;
        $valueLowercase = strtolower($valueString);
        $minLength      = self::MIN_LENGTH;
        $maxLength      = self::MAX_LENGTH;
        $mask           = "/^[a-z]{1,}$/";
        $matches        = [];

        if (strlen($valueLowercase) < $minLength) {
            throw new NormalizingException(
                "top level domain \"$valueString\" is shorter than $minLength"
            );
        }
        if (strlen($valueLowercase) > $maxLength) {
            throw new NormalizingException(
                "top level domain \"$valueString\" is longer than $maxLength"
            );
        }

        preg_match($mask, $valueLowercase, $matches);
        if (!isset($matches[0]) || $matches[0] !== $valueLowercase) {
            throw new NormalizingException(
                "top level domain \"$valueString\" does not matched the pattern $mask"
            );
        }

        return $valueLowercase;
    }
}