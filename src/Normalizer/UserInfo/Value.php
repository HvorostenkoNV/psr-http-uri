<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\UserInfo;

use HNV\Http\Helper\Normalizer\NormalizerInterface;

use function rawurlencode;
use function rawurldecode;
/** ***********************************************************************************************
 * URI user info value normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
abstract class Value implements NormalizerInterface
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
