<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

use function str_replace;

class SchemeRules
{
    public const URI_DELIMITER      = SpecialCharacters::COLON;
    public const ALLOWED_CHARACTERS = [
        SpecialCharacters::PLUS,
        SpecialCharacters::MINUS,
        SpecialCharacters::DOT,
    ];
    public const STANDARD_PORTS     = [
        'tcpmux'    => [1],
        'qotd'      => [17],
        'chargen'   => [19],
        'ftp'       => [20, 21],
        'ssh'       => [22],
        'telnet'    => [23],
        'smtp'      => [25],
        'whois'     => [43],
        'tftp'      => [69],
        'http'      => [80],
        'pop2'      => [109],
        'pop3'      => [110],
        'nntp'      => [119],
        'ntp'       => [123],
        'imap'      => [143],
        'snmp'      => [161],
        'irc'       => [194],
        'https'     => [443],
    ];
    private const MASK              = '/^[a-z]{1}[a-z0-9#SPECIAL_CHARS#]{1,}$/';

    private static ?string $maskReady = null;

    public static function mask(): string
    {
        if (!self::$maskReady) {
            $specialCharacters = '';

            foreach (static::ALLOWED_CHARACTERS as $case) {
                $specialCharacters .= "\\{$case->value}";
            }

            self::$maskReady = str_replace(
                '#SPECIAL_CHARS#',
                $specialCharacters,
                static::MASK
            );
        }

        return self::$maskReady;
    }
}
