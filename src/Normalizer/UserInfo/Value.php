<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\UserInfo;

use HNV\Http\Uri\Normalizer\NormalizerInterface;

use function rawurldecode;
use function rawurlencode;
/** ***********************************************************************************************
 * URI user info value normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Value implements NormalizerInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value): string
    {
        $valueString = (string) $value;

        return rawurlencode(rawurldecode($valueString));
    }
}