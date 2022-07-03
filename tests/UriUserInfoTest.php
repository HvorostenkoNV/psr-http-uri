<?php

declare(strict_types=1);

namespace HNV\Http\UriTests;

use HNV\Http\Uri\Uri;
use HNV\Http\UriTests\CombinationsProvider\UserInfo\CombinedValue as UserInfoCombinationsProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

use function spl_object_id;
use function strlen;

/**
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with user info values.
 *
 * @internal
 * @covers Uri
 * @small
 */
class UriUserInfoTest extends TestCase
{
    /**
     * Test "Uri::withUserInfo" provides new instance of URI.
     *
     * @covers       Uri::withUserInfo
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $login login
     *
     * @throws Throwable
     */
    public function testProvidesNewInstance(string $login): void
    {
        $uriFirst  = new Uri();
        $uriSecond = $uriFirst->withUserInfo($login);
        $uriThird  = $uriSecond->withUserInfo($login);

        static::assertNotSame(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withUserInfo\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
        static::assertNotSame(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withUserInfo\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            'Caught result is "SAME INSTANCE".'
        );
    }

    /**
     * Test "Uri::getUserInfo" provides valid normalized value.
     *
     * @covers       Uri::getUserInfo
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $login           login
     * @param string $password        password
     * @param string $valueNormalized normalized value
     *
     * @throws Throwable
     */
    public function testGetValue(
        string $login,
        string $password,
        string $valueNormalized
    ): void {
        $valueCaught = (new Uri())->withUserInfo($login, $password)->getUserInfo();

        static::assertSame(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withUserInfo->getUserInfo\" returned unexpected result.\n".
            "Action was called with parameters (login => {$login}, password => {$password}).\n".
            "Expected result is \"{$valueNormalized}\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::getUserInfo" provides expects value from empty object.
     *
     * @covers Uri::getUserInfo
     *
     * @throws Throwable
     */
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getUserInfo();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->getUserInfo\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::withUserInfo" clears value on setting empty string.
     *
     * @covers       Uri::withUserInfo
     * @dataProvider dataProviderNormalizedValues
     *
     * @param string $login login
     *
     * @throws Throwable
     */
    public function testClearValue(string $login): void
    {
        $valueCaught = (new Uri())
            ->withUserInfo($login)
            ->withUserInfo('')
            ->getUserInfo();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->withUserInfo->withUserInfo->getUserInfo\" returned unexpected result.\n".
            "Action was called with parameters (login => {$login}, login => \"empty string\").\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Test "Uri::withUserInfo" clears value on setting incorrect data.
     *
     * @covers       Uri::withUserInfo
     * @dataProvider dataProviderValidWithInvalidValues
     *
     * @param string $validValue login valid value
     * @param string $login      login (valid or invalid)
     * @param string $password   password (valid or invalid)
     *
     * @throws Throwable
     */
    public function testClearValueWithInvalidData(
        string $validValue,
        string $login,
        string $password
    ): void {
        $valueCaught = (new Uri())
            ->withUserInfo($validValue)
            ->withUserInfo($login, $password)
            ->getUserInfo();

        static::assertSame(
            '',
            $valueCaught,
            "Action \"Uri->withUserInfo->getUserInfo->getUserInfo\" returned unexpected result.\n".
            "Action was called with parameters (login => {$validValue},".
            " login => {$login} password => {$password}).\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"{$valueCaught}\"."
        );
    }

    /**
     * Data provider: values with their normalized pairs.
     *
     * @return array data
     */
    public function dataProviderNormalizedValues(): array
    {
        $result = [];

        foreach (UserInfoCombinationsProvider::get() as $combination) {
            $result[] = [
                $combination['login'],
                $combination['password'],
                $combination['value'],
            ];
        }

        return $result;
    }

    /**
     * Data provider: valid values with invalid values.
     *
     * @return array data
     */
    public function dataProviderValidWithInvalidValues(): array
    {
        $normalizedValues = $this->dataProviderNormalizedValues();
        $validLogin       = '';
        $result           = [];

        foreach ($normalizedValues as $dataSet) {
            $login = $dataSet[0];

            if (strlen($login) > 0) {
                $validLogin = $login;
                break;
            }
        }

        foreach ($normalizedValues as $dataSet) {
            $login    = $dataSet[0];
            $password = $dataSet[1];
            $userInfo = $dataSet[2];

            if (strlen($userInfo) === 0) {
                $result[] = [$validLogin, $login, $password];
            }
        }

        return $result;
    }
}
