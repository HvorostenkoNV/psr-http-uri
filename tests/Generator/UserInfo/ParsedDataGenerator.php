<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\UserInfo;

use HNV\Http\Uri\Collection\UserInfoRules;
use HNV\Http\UriTests\Generator\AbstractCombinationsGenerator;
use HNV\Http\UriTests\ValueObject\UserInfo\ParsedData as UserInfoParsedData;

class ParsedDataGenerator extends AbstractCombinationsGenerator
{
    /**
     * @return UserInfoParsedData[]
     */
    public function generate(): iterable
    {
        yield from $this->getLoginCombinations();
        yield from $this->getPasswordCombinations();
    }

    private function getLoginCombinations(): iterable
    {
        $delimiter = UserInfoRules::VALUES_SEPARATOR->value;

        foreach ((new LoginNormalizedValuesGenerator())->generate() as $value) {
            yield new UserInfoParsedData(
                $value->value,
                true,
                $value->valueNormalized
            );
            yield new UserInfoParsedData(
                $value->value.$delimiter.$this->password,
                true,
                $value->valueNormalized.$delimiter.$this->passwordNormalized
            );
        }

        foreach ((new LoginInvalidValuesGenerator())->generate() as $value) {
            yield new UserInfoParsedData(
                $value->value,
                false
            );
            yield new UserInfoParsedData(
                $value->value.$delimiter.$this->password,
                false
            );
        }
    }

    private function getPasswordCombinations(): iterable
    {
        $delimiter = UserInfoRules::VALUES_SEPARATOR->value;

        foreach ((new PasswordNormalizedValuesGenerator())->generate() as $value) {
            yield new UserInfoParsedData(
                $this->login.$delimiter.$value->value,
                true,
                $this->loginNormalized.$delimiter.$value->valueNormalized
            );
        }

        foreach ((new PasswordInvalidValuesGenerator())->generate() as $value) {
            yield new UserInfoParsedData(
                $this->login.$delimiter.$value->value,
                false
            );
        }
    }
}
