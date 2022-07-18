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
    Path\InvalidValuesGenerator     as PathInvalidValuesGenerator,
    Path\NormalizedValuesGenerator  as PathNormalizedValuesGenerator,
};
use HNV\Http\UriTests\ValueObject\{
    Authority\Data          as AuthorityData,
    FullString\CombinedData as FullStringCombinedData,
    InvalidValue,
    NormalizedValue,
    UserInfo\Data           as UserInfoData,
};

use function ltrim;

class PathCombinations extends AbstractCombinationsGenerator
{
    /** @var NormalizedValue[] */
    private array $normalizedPaths                      = [];

    /** @var NormalizedValue[] */
    private array $normalizedPathsWithSingleDelimiter   = [];

    /** @var InvalidValue[] */
    private array $invalidPaths                         = [];

    public function __construct()
    {
        parent::__construct();

        foreach ((new PathNormalizedValuesGenerator())->generate() as $value) {
            $pathSeparator                              = PathRules::PARTS_SEPARATOR->value;
            $this->normalizedPaths[]                    = new NormalizedValue(
                $value->value,
                str_starts_with($value->valueNormalized, $pathSeparator)
                    ? $value->valueNormalized
                    : $pathSeparator.$value->valueNormalized
            );
            $this->normalizedPathsWithSingleDelimiter[] = new NormalizedValue(
                $value->value,
                str_starts_with($value->valueNormalized, $pathSeparator)
                    ? $pathSeparator.ltrim($value->valueNormalized, $pathSeparator)
                    : $value->valueNormalized
            );
        }

        foreach ((new PathInvalidValuesGenerator())->generate() as $value) {
            $this->invalidPaths[] = $value;
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
        yield from $this->getValuesWithoutQuery();
        yield from $this->getValuesWithoutFragment();
    }

    private function getFullValues(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedPaths as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $value->value,
                $this->query,
                $this->fragment,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$value->valueNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
        }

        foreach ($this->invalidPaths as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $value->value,
                $this->query,
                $this->fragment,
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
        }
    }

    private function getValuesWithoutScheme(): iterable
    {
        foreach ($this->normalizedPaths as $value) {
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $value->value,
                $this->query,
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
                $value->value,
                '',
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
                $value->value,
                $this->query,
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
                $value->value,
                '',
                '',
                '',
            );
        }

        foreach ($this->invalidPaths as $value) {
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $value->value,
                $this->query,
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
                $value->value,
                '',
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
                $value->value,
                $this->query,
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
                $value->value,
                '',
                '',
                '',
            );
        }
    }

    private function getValuesWithoutAuthority(): iterable
    {
        $schemeDelimiter   = SchemeRules::URI_DELIMITER->value;
        $queryDelimiter    = QueryRules::URI_DELIMITER->value;
        $fragmentDelimiter = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedPathsWithSingleDelimiter as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $value->value,
                $this->query,
                $this->fragment,
                $this->schemeNormalized.$schemeDelimiter
                .$value->valueNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $value->value,
                $this->query,
                $this->fragment,
                $value->valueNormalized
                .$queryDelimiter.$this->queryNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $value->value,
                '',
                $this->fragment,
                $value->valueNormalized
                .$fragmentDelimiter.$this->fragmentNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $value->value,
                $this->query,
                '',
                $value->valueNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $value->value,
                '',
                '',
                $value->valueNormalized,
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $value->value,
                '',
                $this->fragment,
                $this->schemeNormalized.$schemeDelimiter
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
                $value->value,
                $this->query,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$value->valueNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $value->value,
                '',
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$value->valueNormalized
            );
        }

        foreach ($this->invalidPaths as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $value->value,
                $this->query,
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
                $value->value,
                $this->query,
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
                $value->value,
                '',
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
                $value->value,
                $this->query,
                '',
                '',
            );
            yield new FullStringCombinedData(
                '',
                new AuthorityData(
                    new UserInfoData('', ''),
                    '',
                    0,
                ),
                $value->value,
                '',
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
                $value->value,
                '',
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
                $value->value,
                $this->query,
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
                $value->value,
                '',
                '',
                '',
            );
        }
    }

    private function getValuesWithoutQuery(): iterable
    {
        $schemeDelimiter    = SchemeRules::URI_DELIMITER->value;
        $authorityDelimiter = AuthorityRules::URI_DELIMITER;
        $fragmentDelimiter  = FragmentRules::URI_DELIMITER->value;

        foreach ($this->normalizedPaths as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $value->value,
                '',
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
                $value->value,
                '',
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$value->valueNormalized,
            );
        }

        foreach ($this->invalidPaths as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $value->value,
                '',
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
                $value->value,
                '',
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
        $queryDelimiter     = QueryRules::URI_DELIMITER->value;

        foreach ($this->normalizedPaths as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $value->value,
                $this->query,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$value->valueNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }

        foreach ($this->invalidPaths as $value) {
            yield new FullStringCombinedData(
                $this->scheme,
                new AuthorityData(
                    new UserInfoData($this->login, $this->password),
                    $this->host,
                    $this->port,
                ),
                $value->value,
                $this->query,
                '',
                $this->schemeNormalized.$schemeDelimiter
                .$authorityDelimiter.$this->authorityNormalized
                .$queryDelimiter.$this->queryNormalized,
            );
        }
    }
}
