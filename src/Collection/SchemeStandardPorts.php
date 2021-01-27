<?php
declare(strict_types=1);

namespace HNV\Http\Uri\Collection;

use HNV\Http\Helper\Collection\CollectionInterface;
/** ***********************************************************************************************
 * URI scheme standard ports collection.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class SchemeStandardPorts implements CollectionInterface
{
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public static function get(): array
    {
        return [
            1   => 'tcpmux',
            17  => 'qotd',
            19  => 'chargen',
            20  => 'ftp',
            21  => 'ftp',
            22  => 'ssh',
            23  => 'telnet',
            25  => 'smtp',
            43  => 'whois',
            69  => 'tftp',
            80  => 'http',
            109 => 'pop2',
            110 => 'pop3',
            119 => 'nntp',
            123 => 'ntp',
            143 => 'imap',
            161 => 'snmp',
            194 => 'irc',
            443 => 'https',
        ];
    }
}