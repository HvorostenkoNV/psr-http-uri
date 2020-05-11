<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\UserInfo;

use HNV\Http\Uri\Normalizer\NormalizerInterface;
/** ***********************************************************************************************
 * URI user login normalizer.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class Login implements NormalizerInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value)
    {
        return Value::normalize($value);
    }
}