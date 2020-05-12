<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;
/** ***********************************************************************************************
 * Normalizer interface.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
interface NormalizerInterface
{
    /** **********************************************************************
     * Normalize data.
     *
     * @param   mixed $value                Value.
     *
     * @return  mixed                       Normalized value.
     * @throws  NormalizingException        Normalizing error.
     ************************************************************************/
    public static function normalize($value);
}