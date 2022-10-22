<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\SpecialCharacters;

use function in_array;

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

    public static function mask(): string
    {
        $letter             = 'a-z';
        $digit              = '0-9';
        $specialCharacters  = '';

        foreach (static::ALLOWED_CHARACTERS as $case) {
            $specialCharacters .= "\\{$case->value}";
        }

        return "/^[{$letter}]{1}[{$letter}{$digit}{$specialCharacters}]{1,}$/";
    }

    public static function isStandardPort(string $scheme, int $port): bool
    {
        return
            isset(static::STANDARD_PORTS[$scheme])
            && in_array($port, static::STANDARD_PORTS[$scheme]);
    }
}
