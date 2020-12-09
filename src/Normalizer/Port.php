<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use function is_numeric;
/** ***********************************************************************************************
 * URI port normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Port implements NormalizerInterface
{
    public const MIN_VALUE = 0;
    public const MAX_VALUE = 65535;
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value): int
    {
        if (!is_numeric($value)) {
            $valueString = (string) $value;
            throw new NormalizingException("value \"$valueString\" is not numeric");
        }

        $valueInt   = (int) $value;
        $minValue   = self::MIN_VALUE;
        $maxValue   = self::MAX_VALUE;

        if ($valueInt < $minValue) {
            throw new NormalizingException("port \"$valueInt\" is less then $minValue");
        }
        if ($valueInt > $maxValue) {
            throw new NormalizingException("port \"$valueInt\" is grater then $minValue");
        }

        return $valueInt;
    }
}