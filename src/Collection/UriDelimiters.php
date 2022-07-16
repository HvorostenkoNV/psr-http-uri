<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

use function array_unique;
use function str_split;

class UriDelimiters
{
    private const GENERAL_DELIMITERS    = [
        SchemeRules::URI_DELIMITER,
        AuthorityRules::URI_DELIMITER,
        UserInfoRules::URI_DELIMITER,
        IpAddressV6Rules::LEFT_FRAME,
        IpAddressV6Rules::RIGHT_FRAME,
        PortRules::URI_DELIMITER,
        QueryRules::URI_DELIMITER,
        FragmentRules::URI_DELIMITER,
    ];
    private const OTHER_SUB_DELIMITERS  = [
        SpecialCharacters::EXCLAMATION_POINT,
        SpecialCharacters::DOLLAR,
        SpecialCharacters::APOSTROPHE,
        SpecialCharacters::OPEN_PARENTHESIS,
        SpecialCharacters::CLOSE_PARENTHESIS,
        SpecialCharacters::ASTERISK,
        SpecialCharacters::PLUS,
        SpecialCharacters::COMMA,
        SpecialCharacters::SEMICOLON,
    ];

    private static array $readyCollection = [];

    /**
     * @return SpecialCharacters[]
     */
    public static function generalDelimiters(): array
    {
        if (!isset(self::$readyCollection['general_delimiters'])) {
            self::$readyCollection['general_delimiters']    = [];
            $characters                                     = [];

            foreach (self::GENERAL_DELIMITERS as $delimiter) {
                if ($delimiter instanceof SpecialCharacters) {
                    $characters[] = $delimiter->value;
                } else {
                    foreach (str_split($delimiter) as $character) {
                        $characters[] = $character;
                    }
                }
            }

            foreach (array_unique($characters) as $character) {
                self::$readyCollection['general_delimiters'][] = SpecialCharacters::from($character);
            }
        }

        return self::$readyCollection['general_delimiters'];
    }

    /**
     * @return SpecialCharacters[]
     */
    public static function otherSubDelimiters(): array
    {
        return static::OTHER_SUB_DELIMITERS;
    }
}
