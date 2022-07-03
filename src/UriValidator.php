<?php

declare(strict_types=1);

namespace HNV\Http\Uri;

use RuntimeException;

use function strlen;

/**
 * URI validator class.
 */
class UriValidator
{
    /**
     * Check authority is valid.
     *
     * @param string $userInfo user info
     * @param string $host     host
     * @param int    $port     port
     *
     * @throws RuntimeException validation failed
     */
    public static function checkAuthorityIsValid(
        string $userInfo = '',
        string $host = '',
        int $port = 0
    ): void {
        $invalidConditions = [
            strlen($userInfo) > 0 && strlen($host) === 0,
            $port > 0 && strlen($host) === 0,
        ];

        foreach ($invalidConditions as $condition) {
            if ($condition) {
                throw new RuntimeException(
                    "authority with user info \"{$userInfo}\", ".
                    "host \"{$host}\" and port \"{$port}\" can not be build"
                );
            }
        }
    }

    /**
     * Check URI is valid.
     *
     * @param string $scheme    scheme
     * @param string $authority authority
     * @param string $path      path
     *
     * @throws RuntimeException validation failed
     */
    public static function checkUriIsValid(
        string $scheme = '',
        string $authority = '',
        string $path = ''
    ): void {
        $validConditions = [
            strlen($scheme) > 0 && strlen($authority) > 0 && strlen($path) > 0,
            strlen($scheme) > 0 && strlen($authority) === 0 && strlen($path) > 0,
            strlen($scheme) > 0 && strlen($authority) > 0 && strlen($path) === 0,
            strlen($scheme) === 0 && strlen($authority) === 0 && strlen($path) > 0,
        ];

        foreach ($validConditions as $condition) {
            if ($condition) {
                return;
            }
        }

        throw new RuntimeException(
            "URI with scheme \"{$scheme}\", ".
            "authority \"{$authority}\" and path \"{$path}\" can not be build"
        );
    }
}
