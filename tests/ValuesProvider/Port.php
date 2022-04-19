<?php
declare(strict_types=1);

namespace HNV\Http\UriTests\ValuesProvider;

use HNV\Http\Uri\Normalizer\Port as PortNormalizer;

use function rand;
/** ***********************************************************************************************
 * URI port normalized values set provider.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Port implements ValuesProviderInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function getValidValues(): array
    {
        $result = [
            PortNormalizer::MAX_VALUE => PortNormalizer::MAX_VALUE,
        ];

        for ($iteration = 10; $iteration > 0; $iteration--) {
            $randomValue = rand(PortNormalizer::MIN_VALUE + 1, PortNormalizer::MAX_VALUE - 1);

            $result[$randomValue] = $randomValue;
        }

        return $result;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function getInvalidValues(): array
    {
        $result = [
            PortNormalizer::MIN_VALUE - 1,
            PortNormalizer::MAX_VALUE + 1,
        ];

        for ($iteration = 5; $iteration > 0; $iteration--) {
            $result[]   = rand(PortNormalizer::MIN_VALUE - 100, PortNormalizer::MIN_VALUE - 2);
            $result[]   = rand(PortNormalizer::MAX_VALUE + 2,   PortNormalizer::MAX_VALUE + 100);
        }

        return $result;
    }
}
