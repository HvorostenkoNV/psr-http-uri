<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\UserInfo;

use HNV\Http\Helper\Normalizer\NormalizerInterface;
/** ***********************************************************************************************
 * URI user login normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Login implements NormalizerInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value): string
    {
        return Value::normalize($value);
    }
}