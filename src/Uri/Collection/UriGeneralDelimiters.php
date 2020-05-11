<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use function count;
use function str_split;
use function array_unique;
/** ***********************************************************************************************
 * URI general delimiters collection.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class UriGeneralDelimiters implements CollectionInterface
{
    public const SCHEME_DELIMITER           = ':';
    public const AUTHORITY_DELIMITER        = '//';
    public const USER_INFO_DELIMITER        = '@';
    public const IP_ADDRESS_V6_LEFT_FRAME   = '[';
    public const IP_ADDRESS_V6_RIGHT_FRAME  = ']';
    public const PORT_DELIMITER             = ':';
    public const QUERY_DELIMITER            = '?';
    public const FRAGMENT_DELIMITER         = '#';

    private static $collection = [];
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        if (count(self::$collection) === 0) {
            $delimiters         = [
                self::SCHEME_DELIMITER,
                self::AUTHORITY_DELIMITER,
                self::USER_INFO_DELIMITER,
                self::IP_ADDRESS_V6_LEFT_FRAME,
                self::IP_ADDRESS_V6_RIGHT_FRAME,
                self::PORT_DELIMITER,
                self::QUERY_DELIMITER,
                self::FRAGMENT_DELIMITER,
            ];
            $delimitersChars    = [];

            foreach ($delimiters as $value) {
                foreach (str_split($value) as $char) {
                    $delimitersChars[] = $char;
                }
            }

            self::$collection = array_unique($delimitersChars);
        }

        return self::$collection;
    }
}