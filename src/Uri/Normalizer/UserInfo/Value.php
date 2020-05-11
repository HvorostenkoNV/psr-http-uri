<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\UserInfo;

use HNV\Http\Uri\Normalizer\NormalizerInterface;

use function rawurldecode;
use function rawurlencode;
/** ***********************************************************************************************
 * URI user info value normalizer.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class Value implements NormalizerInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value)
    {
        $valueString = (string) $value;

        return rawurlencode(rawurldecode($valueString));
    }
}