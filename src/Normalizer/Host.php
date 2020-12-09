<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer;

use HNV\Http\Uri\Collection\UriGeneralDelimiters;
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
    public static function normalize($value): string
    {
        $valueString = (string) $value;

        try {
            return IpAddressV4Normalizer::normalize($valueString);
        } catch (NormalizingException) {

        }

        try {
            $leftBracer         = UriGeneralDelimiters::IP_ADDRESS_V6_LEFT_FRAME;
            $rightBracer        = UriGeneralDelimiters::IP_ADDRESS_V6_RIGHT_FRAME;
            $valueTrim          = trim($valueString, $leftBracer.$rightBracer);
            $valueNormalized    = IpAddressV6Normalizer::normalize($valueTrim);

            return $leftBracer.$valueNormalized.$rightBracer;
        } catch (NormalizingException) {

        }

        try {
            return DomainNameNormalizer::normalize($valueString);
        } catch (NormalizingException) {

        }

        throw new NormalizingException("value \"$valueString\" is not valid host");
    }
}