<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Normalizer\DomainName;

use HNV\Http\Helper\Normalizer\{
    NormalizerInterface,
    NormalizingException,
};

use function explode;
use function implode;
use function count;
use function array_pop;
/** ***********************************************************************************************
 * Full qualified domain name normalizer.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class FullQualifiedDomainName implements NormalizerInterface
{
    public const PARTS_DELIMITER = '.';
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function normalize($value): string
    {
        $valueString    = (string) $value;
        $valueExploded  = explode(self::PARTS_DELIMITER, $valueString);

        if (count($valueExploded) < 2) {
            throw new NormalizingException("domain \"$valueString\" has not enough parts");
        }

        $topLevelDomain     = array_pop($valueExploded);
        $normalizedParts    = [];

        foreach ($valueExploded as $subDomain) {
            try {
                $normalizedParts[] = SubLevelDomain::normalize($subDomain);
            } catch (NormalizingException $exception) {
                throw new NormalizingException(
                    "sub domain \"$subDomain\" is invalid",
                    0,
                    $exception
                );
            }
        }

        try {
            $normalizedParts[] = TopLevelDomain::normalize($topLevelDomain);
        } catch (NormalizingException $exception) {
            throw new NormalizingException(
                "top level domain \"$topLevelDomain\" is invalid",
                0,
                $exception
            );
        }

        return implode(self::PARTS_DELIMITER, $normalizedParts);
    }
}
