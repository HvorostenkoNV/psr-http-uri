<?php

declare(strict_types=1);

namespace HNV\Http\UriTests\Generator\UserInfo;

use HNV\Http\Uri\Collection\UserInfoRules;
use HNV\Http\UriTests\Generator\AbstractCombinationsGenerator;
use HNV\Http\UriTests\ValueObject\UserInfo\CombinedData as UserInfoCombinedData;

class CombinedDataGenerator extends AbstractCombinationsGenerator
{
    /**
     * @return UserInfoCombinedData[]
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
            yield new UserInfoCombinedData(
                $value->value,
                '',
                $value->valueNormalized
            );
            yield new UserInfoCombinedData(
                $value->value,
                $this->password,
                $value->valueNormalized.$delimiter.$this->passwordNormalized
            );
        }

        foreach ((new LoginInvalidValuesGenerator())->generate() as $value) {
            yield new UserInfoCombinedData(
                $value->value,
                $this->password,
                ''
            );
            yield new UserInfoCombinedData(
                $value->value,
                '',
                ''
            );
        }
    }

    private function getPasswordCombinations(): iterable
    {
        $delimiter = UserInfoRules::VALUES_SEPARATOR->value;

        foreach ((new PasswordNormalizedValuesGenerator())->generate() as $value) {
            yield new UserInfoCombinedData(
                $this->login,
                $value->value,
                $this->loginNormalized.$delimiter.$value->valueNormalized
            );
            yield new UserInfoCombinedData(
                '',
                $value->value,
                ''
            );
        }

        foreach ((new PasswordInvalidValuesGenerator())->generate() as $value) {
            yield new UserInfoCombinedData(
                $this->login,
                $value->value,
                ''
            );
            yield new UserInfoCombinedData(
                '',
                $value->value,
                ''
            );
        }
    }
}
