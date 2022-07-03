<?php

declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use function in_array;

/**
 * URI scheme standard ports collection.
 */
enum SchemeStandardPorts: string
{
    case TCPMUX     = 'tcpmux';
    case QOTD       = 'qotd';
    case CHARGEN    = 'chargen';
    case FTP        = 'ftp';
    case SSH        = 'ssh';
    case TELNET     = 'telnet';
    case SMTP       = 'smtp';
    case WHOIS      = 'whois';
    case TFTP       = 'tftp';
    case HTTP       = 'http';
    case POP2       = 'pop2';
    case POP3       = 'pop3';
    case NNTP       = 'nntp';
    case NTP        = 'ntp';
    case IMAP       = 'imap';
    case SNMP       = 'snmp';
    case IRC        = 'irc';
    case HTTPS      = 'https';
    /**
     * Get ports set for given scheme.
     *
     * @return int[] ports set
     */
    public function ports(): array
    {
        return match ($this) {
            self::TCPMUX    => [1],
            self::QOTD      => [17],
            self::CHARGEN   => [19],
            self::FTP       => [20, 21],
            self::SSH       => [22],
            self::TELNET    => [23],
            self::SMTP      => [25],
            self::WHOIS     => [43],
            self::TFTP      => [69],
            self::HTTP      => [80],
            self::POP2      => [109],
            self::POP3      => [110],
            self::NNTP      => [119],
            self::NTP       => [123],
            self::IMAP      => [143],
            self::SNMP      => [161],
            self::IRC       => [194],
            self::HTTPS     => [443],
        };
    }

    /**
     * Try to find scheme by port.
     *
     * @param int $port port for search
     *
     * @return null|self self or non
     */
    public static function findByPort(int $port): ?self
    {
        foreach (self::cases() as $case) {
            if (in_array($port, $case->ports(), true)) {
                return $case;
            }
        }

        return null;
    }
}
