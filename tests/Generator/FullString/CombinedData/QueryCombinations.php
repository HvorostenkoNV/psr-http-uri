<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\FullString\CombinedData;

use HNV\Http\Uri\Collection\{
    AuthorityRules,
    FragmentRules,
    PathRules,
    QueryRules,
    SchemeRules,
};
use HNV\Http\UriTests\Generator\{
    AbstractCombinationsGenerator,
    Query\InvalidValuesGenerator    as QueryInvalidValuesGenerator,
    Query\NormalizedValuesGenerator as QueryNormalizedValuesGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    Authority\Data          as AuthorityData,
    FullString\CombinedData as FullStringCombinedData,
    InvalidValue,
    NormalizedValue,
    UserInfo\Data           as UserInfoData,
};

use function strlen;

class QueryCombinations extends AbstractCombinationsGenerator
{
    /** @var NormalizedValue[] */
    private array $normalizedQuery  = [];

    /** @var InvalidValue[] */
    private array $invalidQuery     = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new QueryNormalizedValuesGenerator())->generate() as $value) {
            $this->normalizedQuery[] = new NormalizedValue(
                $value->value,
                strlen($value->valueNormalized) > 0
                    ? QueryRules::URI_DELIMITER->value.$value->valueNormalized
                    : ''
            );
        }

        foreach ((new QueryInvalidValuesGenerator())->generate() as $value) {
            $this->invalidQuery[] = $value;
        }
    }

    /**
     * @return FullStringCombinedData[]
     */
    public function generate(): iterable
    {
        yield from $this->getFullValues();
        yield from $this->getValuesWithoutScheme();
        yield from $this->getValuesWithoutAuthority();
        yield from $this->getValuesWithoutPath();
        yield from $this->getValuesWithoutFragment();
    }

    private function getFullValues(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedQuery as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $value->value,
                $this->fragment,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$value->valueNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
        }

        foreach ($this->invalidQuery as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $value->value,
                $this->fragment,
                '',
            );
        }
    }

    private function getValuesWithoutScheme(): iterable
    {
        $pathDelimiter     = PathRules::PARTS_SEPARATOR->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedQuery as $value) {
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $value->value,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $value->value,
                $this->fragment,
                $this->pathNormalized
                .$value->valueNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $value->value,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $value->value,
                '',
                $this->pathNormalized
                .$value->valueNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $value->value,
                '',
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $value->value,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $value->value,
                '',
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $value->value,
                '',
                '',
            );
        }

        foreach ($this->invalidQuery as $value) {
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $value->value,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $value->value,
                $this->fragment,
                $this->pathNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $value->value,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $value->value,
                '',
                $pathDelimiter.$this->pathNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $value->value,
                '',
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $value->value,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $value->value,
                '',
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $value->value,
                '',
                '',
            );
        }
    }

    private function getValuesWithoutAuthority(): iterable
    {
        $schemeDelimiter   = SchemeRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedQuery as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $value->value,
                $this->fragment,
                $this->schemeNormalized.$schemeDelimiter.$this->pathNormalized
                .$value->valueNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $value->value,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $value->value,
                '',
                '',
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $value->value,
                '',
                $this->schemeNormalized.$schemeDelimiter.$this->pathNormalized
                .$value->valueNormalized,
            );
        }

        foreach ($this->invalidQuery as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $value->value,
                $this->fragment,
                $this->schemeNormalized.$schemeDelimiter.$this->pathNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $value->value,
                $this->fragment,
                '',
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                '',
                $value->value,
                '',
                '',
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $this->path,
                $value->value,
                '',
                $this->schemeNormalized.$schemeDelimiter.$this->pathNormalized,
            );
        }
    }

    private function getValuesWithoutPath(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedQuery as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $value->value,
                $this->fragment,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$value->valueNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $value->value,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$value->valueNormalized,
            );
        }

        foreach ($this->invalidQuery as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $value->value,
                $this->fragment,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                '',
                $value->value,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized,
            );
        }
    }

    private function getValuesWithoutFragment(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $pathDelimiter      = PathRules::PARTS_SEPARATOR->value;

        foreach ($this->normalizedQuery as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $value->value,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized
                .$value->valueNormalized,
            );
        }

        foreach ($this->invalidQuery as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $this->path,
                $value->value,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$pathDelimiter.$this->pathNormalized,
            );
        }
    }
}
