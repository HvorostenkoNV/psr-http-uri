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

use function ltrim;
/** ***********************************************************************************************
 * PSR-7 UriInterface implementation.
 *
 * @package HNV\Psr\Http
 * @author  Hvorostenko
 *************************************************************************************************/
class Uri implements UriInterface
{
    private $scheme     = '';
    private $host       = '';
    private $port       = 0;
    private $user       = '';
    private $password   = '';
    private $path       = '';
    private $query      = '';
    private $fragment   = '';
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function withScheme(string $scheme): UriInterface
    {
        try {
            $newInstance = clone $this;
            $newInstance->scheme = $scheme !== ''
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
            $newInstance->user      = $user !== ''
                ? UserLoginNormalizer::normalize($user)
                : '';
            $newInstance->password  = $password !== '' && $user !== ''
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
        return $this->user !== '' && $this->password !== ''
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
            $newInstance->host = $host !== ''
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

        if ($host !== '' && $userInfo !== '' && $port !== null) {
            return
                $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                $host.
                UriGeneralDelimiters::PORT_DELIMITER.$port;
        }
        if ($host !== '' && $userInfo !== '') {
            return
                $userInfo.UriGeneralDelimiters::USER_INFO_DELIMITER.
                $host;
        }
        if ($host !== '') {
            return $host;
        }

        return '';
    }
    /** **********************************************************************
     * @inheritDoc
     ************************************************************************/
    public function withPath(string $path): UriInterface
    {
        try {
            $newInstance = clone $this;
            $newInstance->path = $path !== ''
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
            $newInstance->query = $query !== ''
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
            $newInstance->fragment = $fragment !== ''
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

        if ($authority === '' && $path === '') {
            return '';
        }

        if ($scheme !== '') {
            $result .= $scheme.UriGeneralDelimiters::SCHEME_DELIMITER;
        }
        if ($authority !== '') {
            $result .= UriGeneralDelimiters::AUTHORITY_DELIMITER.$authority;
        }
        if ($path !== '') {
            $pathPrefix                 = UriSubDelimiters::PATH_PARTS_SEPARATOR;
            $pathHasPrefix              = $path[0] === $pathPrefix;
            $pathHasMultiplePrefixes    = $path[0] === $pathPrefix && $path[1] === $pathPrefix;

            if ($authority !== '' && !$pathHasPrefix) {
                $result .= $pathPrefix.$path;
            } elseif ($authority === '' && $pathHasMultiplePrefixes) {
                $result .= $pathPrefix.ltrim($path, $pathPrefix);
            } else {
                $result .= $path;
            }
        }
        if ($query !== '') {
            $result .= UriGeneralDelimiters::QUERY_DELIMITER.$query;
        }
        if ($fragment !== '') {
            $result .= UriGeneralDelimiters::FRAGMENT_DELIMITER.$fragment;
        }

        return $result;
    }
}