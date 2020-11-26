<?php
declare(strict_types=1);

namespace HNV\Http\Uri;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use HNV\Http\Uri\Normalizer\{
    NormalizingException,
    Scheme              as SchemeNormalizer,
    UserInfo\Login      as UserLoginNormalizer,
    UserInfo\Password   as UserPasswordNormalizer,
    Host                as HostNormalizer,
    Port                as PortNormalizer,
    Path                as PathNormalizer,
    Query               as QueryNormalizer,
    Fragment            as FragmentNormalizer
};
use HNV\Http\Uri\Collection\{
    UriGeneralDelimiters,
    UriSubDelimiters,
    SchemeStandardPorts
};

use function is_null;
use function strlen;
use function substr;
use function ltrim;
/** ***********************************************************************************************
 * PSR-7 UriInterface implementation.
 *
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class Uri implements UriInterface
{
    private string  $scheme     = '';
    private string  $host       = '';
    private int     $port       = 0;
    private string  $user       = '';
    private string  $password   = '';
    private string  $path       = '';
    private string  $query      = '';
    private string  $fragment   = '';
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function withScheme(string $scheme): UriInterface
    {
        try {
            $newInstance = clone $this;
            $newInstance->scheme = strlen($scheme) > 0
                ? SchemeNormalizer::normalize($scheme)
                : '';

            return $newInstance;
        } catch (NormalizingException $exception) {
            throw new InvalidArgumentException(
                "scheme \"$scheme\" is invalid",
                0,
                $exception
            );
        }
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function getScheme(): string
    {
        return $this->scheme;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function withUserInfo(string $user, string $password = ''): UriInterface
    {
        $newInstance = clone $this;

        try {
            $newInstance->user      = strlen($user) > 0
                ? UserLoginNormalizer::normalize($user)
                : '';
            $newInstance->password  = strlen($password) > 0 && strlen($user) > 0
                ? UserPasswordNormalizer::normalize($password)
                : '';
        } catch (NormalizingException $exception) {
            $newInstance->user      = '';
            $newInstance->password  = '';
        }

        return $newInstance;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function getUserInfo(): string
    {
        return strlen($this->user) > 0 && strlen($this->password) > 0
            ? $this->user.UriSubDelimiters::USER_INFO_SEPARATOR.$this->password
            : $this->user;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function withHost(string $host): UriInterface
    {
        try {
            $newInstance = clone $this;
            $newInstance->host = strlen($host) > 0
                ? HostNormalizer::normalize($host)
                : '';

            return $newInstance;
        } catch (NormalizingException $exception) {
            throw new InvalidArgumentException(
                "host \"$host\" is invalid",
                0,
                $exception
            );
        }
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function getHost(): string
    {
        return $this->host;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function withPort(int $port = 0): UriInterface
    {
        try {
            $newInstance = clone $this;
            $newInstance->port = $port !== 0
                ? PortNormalizer::normalize($port)
                : 0;

            return $newInstance;
        } catch (NormalizingException $exception) {
            throw new InvalidArgumentException(
                "port \"$port\" is invalid",
                0,
                $exception
            );
        }
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function getPort(): ?int
    {
        $port           = $this->port;
        $scheme         = $this->getScheme();
        $standardPorts  = SchemeStandardPorts::get();
        $portIsStandard = isset($standardPorts[$port]) && $standardPorts[$port] === $scheme;

        return $port === 0 || $portIsStandard ? null : $port;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function getAuthority(): string
    {
        $userInfo   = $this->getUserInfo();
        $host       = $this->getHost();
        $port       = $this->getPort();

        if (strlen($host) === 0) {
            return '';
        }

        $result = $host;
        if (strlen($userInfo) > 0) {
            $result = $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.$result;
        }
        if (!is_null($port)) {
            $result = $result.UriGeneralDelimiters::PORT_DELIMITER.$port;
        }

        return $result;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function withPath(string $path): UriInterface
    {
        try {
            $newInstance = clone $this;
            $newInstance->path = strlen($path) > 0
                ? PathNormalizer::normalize($path)
                : '';

            return $newInstance;
        } catch (NormalizingException $exception) {
            throw new InvalidArgumentException(
                "path \"$path\" is invalid",
                0,
                $exception
            );
        }
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function getPath(): string
    {
        return $this->path;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function withQuery(string $query): UriInterface
    {
        try {
            $newInstance = clone $this;
            $newInstance->query = strlen($query) > 0
                ? QueryNormalizer::normalize($query)
                : '';

            return $newInstance;
        } catch (NormalizingException $exception) {
            throw new InvalidArgumentException(
                "query \"$query\" is invalid",
                0,
                $exception
            );
        }
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function getQuery(): string
    {
        return $this->query;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function withFragment(string $fragment): UriInterface
    {
        $newInstance = clone $this;

        try {
            $newInstance->fragment = strlen($fragment) > 0
                ? FragmentNormalizer::normalize($fragment)
                : '';
        } catch (NormalizingException $exception) {
            $newInstance->fragment = '';
        }

        return $newInstance;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function getFragment(): string
    {
        return $this->fragment;
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function __toString(): string
    {
        $scheme     = $this->getScheme();
        $authority  = $this->getAuthority();
        $path       = $this->getPath();
        $query      = $this->getQuery();
        $fragment   = $this->getFragment();
        $result     = '';

        if (strlen($authority) === 0 && strlen($path) === 0) {
            return '';
        }

        if (strlen($scheme) > 0) {
            $result .= $scheme.UriGeneralDelimiters::SCHEME_DELIMITER;
        }
        if (strlen($authority) > 0) {
            $result .= UriGeneralDelimiters::AUTHORITY_DELIMITER.$authority;
        }
        if (strlen($path) > 0) {
            $pathPrefix                 = UriSubDelimiters::PATH_PARTS_SEPARATOR;
            $pathHasPrefix              = $path[0] === $pathPrefix;
            $pathHasMultiplePrefixes    = substr($path, 0, 2) === $pathPrefix.$pathPrefix;

            if (strlen($authority) > 0 && !$pathHasPrefix) {
                $result .= $pathPrefix.$path;
            } elseif (strlen($authority) === 0 && $pathHasMultiplePrefixes) {
                $result .= $pathPrefix.ltrim($path, $pathPrefix);
            } else {
                $result .= $path;
            }
        }
        if (strlen($query) > 0) {
            $result .= UriGeneralDelimiters::QUERY_DELIMITER.$query;
        }
        if (strlen($fragment) > 0) {
            $result .= UriGeneralDelimiters::FRAGMENT_DELIMITER.$fragment;
        }

        return $result;
    }
}