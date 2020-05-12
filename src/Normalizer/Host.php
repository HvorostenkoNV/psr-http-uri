<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Uri\Normalizer\{
    IpAddress\V4                        as IpAddressV4Normalizer,
    IpAddress\V6                        as IpAddressV6Normalizer,
    DomainName\FullQualifiedDomainName  as DomainNameNormalizer
};
/** ***********************************************************************************************
 * URI host normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Host implements NormalizerInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value)
    {
        $valueString = (string) $value;

        try {
            return IpAddressV4Normalizer::normalize($valueString);
        } catch (NormalizingException $exception) {

        }

        try {
            $valueTrim          = trim($valueString, '[]');
            $valueNormalized    = IpAddressV6Normalizer::normalize($valueTrim);

            return "[$valueNormalized]";
        } catch (NormalizingException $exception) {

        }

        try {
            return DomainNameNormalizer::normalize($valueString);
        } catch (NormalizingException $exception) {

        }

        throw new NormalizingException("value \"$valueString\" is not valid host");
    }
}