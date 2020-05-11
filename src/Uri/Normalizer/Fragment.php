<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use function rawurldecode;
/** ***********************************************************************************************
 * URI fragment normalizer.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class Fragment implements NormalizerInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value)
    {
        $valueString = (string) $value;

        return rawurldecode($valueString);
    }
}