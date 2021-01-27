<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Helper\Normalizer\NormalizerInterface;

use function rawurldecode;
/** ***********************************************************************************************
 * URI fragment normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Fragment implements NormalizerInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value): string
    {
        $valueString = (string) $value;

        return rawurldecode($valueString);
    }
}