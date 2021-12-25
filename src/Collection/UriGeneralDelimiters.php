<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\CollectionInterface;

use function count;
/** ***********************************************************************************************
 * URI general delimiters collection.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UriGeneralDelimiters implements CollectionInterface
{
    use CharactersSetProviderTrait;

    public const SCHEME_DELIMITER           = ':';
    public const AUTHORITY_DELIMITER        = '//';
    public const USER_INFO_DELIMITER        = '@';
    public const IP_ADDRESS_V6_LEFT_FRAME   = '[';
    public const IP_ADDRESS_V6_RIGHT_FRAME  = ']';
    public const PORT_DELIMITER             = ':';
    public const QUERY_DELIMITER            = '?';
    public const FRAGMENT_DELIMITER         = '#';

    private static array $collection = [];
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        if (count(self::$collection) === 0) {
            self::$collection = self::getUniqueSingleCharactersSet([
                self::SCHEME_DELIMITER,
                self::AUTHORITY_DELIMITER,
                self::USER_INFO_DELIMITER,
                self::IP_ADDRESS_V6_LEFT_FRAME,
                self::IP_ADDRESS_V6_RIGHT_FRAME,
                self::PORT_DELIMITER,
                self::QUERY_DELIMITER,
                self::FRAGMENT_DELIMITER,
            ]);
        }

        return self::$collection;
    }
}
