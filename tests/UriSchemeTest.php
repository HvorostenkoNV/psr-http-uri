<?php
declare(strict_types=1);

namespace HNV\Http\UriTests;

use Throwable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use HNV\Http\UriTests\ValuesProvider\Scheme as SchemeValuesProvider;
use HNV\Http\Uri\Uri;

use function spl_object_id;
/** ***********************************************************************************************
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with scheme values.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UriSchemeTest extends TestCase
{
    /** **********************************************************************
     * Test "Uri::withScheme" provides new instance of URI.
     *
     * @covers          Uri::withScheme
     * @dataProvider    dataProviderNormalizedValues
     *
     * @param           string $value               Value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testProvidesNewInstance(string $value): void
    {
        $uriFirst   = new Uri();
        $uriSecond  = $uriFirst->withScheme($value);
        $uriThird   = $uriSecond->withScheme($value);

        self::assertNotEquals(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withScheme\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            "Caught result is \"SAME INSTANCE\"."
        );
        self::assertNotEquals(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withScheme\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            "Caught result is \"SAME INSTANCE\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::getScheme" provides valid normalized value.
     *
     * @covers          Uri::getScheme
     * @dataProvider    dataProviderNormalizedValues
     *
     * @param           string  $value              Value.
     * @param           string  $valueNormalized    Normalized value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testGetValue(string $value, string $valueNormalized): void
    {
        $valueCaught = (new Uri())->withScheme($value)->getScheme();

        self::assertEquals(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withScheme->getScheme\" returned unexpected result.\n".
            "Action was called with parameters (value => $value).\n".
            "Expected result is \"$valueNormalized\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::getScheme" provides expects value from empty object.
     *
     * @covers  Uri::getScheme
     *
     * @return  void
     * @throws  Throwable
     ************************************************************************/
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getScheme();

        self::assertEquals(
            '',
            $valueCaught,
            "Action \"Uri->getScheme\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::withScheme" clears value on setting empty string.
     *
     * @covers          Uri::withScheme
     * @dataProvider    dataProviderNormalizedValues
     *
     * @param           string $value               Value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testClearValue(string $value): void
    {
        $valueCaught = (new Uri())
            ->withScheme($value)
            ->withScheme('')
            ->getScheme();

        self::assertEquals(
            '',
            $valueCaught,
            "Action \"Uri->withScheme->withScheme->getScheme\" returned unexpected result.\n".
            "Action was called with parameters (value => $value, value => \"empty string\").\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::withScheme" throws exception with invalid argument.
     *
     * @covers          Uri::withScheme
     * @dataProvider    dataProviderInvalidValues
     *
     * @param           string $value               Invalid value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testThrowsException(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new Uri())->withScheme($value);

        self::fail(
            "Action \"Uri->withScheme\" threw no expected exception.\n".
            "Action was called with parameters (value => $value).\n".
            "Expects \"InvalidArgumentException\" exception.\n".
            'Caught no exception.'
        );
    }
    /** **********************************************************************
     * Test "Uri::withScheme" saves previous value with setting new invalid value.
     *
     * @covers          Uri::withScheme
     * @dataProvider    dataProviderValidWithInvalidValues
     *
     * @param           string  $value              Valid value.
     * @param           string  $valueNormalized    Normalized valid value.
     * @param           string  $invalidValue       Invalid value.
     *
     * @return          void
     * @throws          Throwable
     ************************************************************************/
    public function testSavesPreviousValueOnError(
        string  $value,
        string  $valueNormalized,
        string  $invalidValue
    ): void {
        $uri = (new Uri())->withScheme($value);

        try {
            $uri->withScheme($invalidValue);
        } catch (InvalidArgumentException) {

        }

        $valueCaught = $uri->getScheme();

        self::assertEquals(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withScheme->withScheme->getScheme\" returned unexpected result.\n".
            "Action was called with parameters (value => $value (valid value),".
            " value => $invalidValue (invalid value)).\n".
            "Expected result is \"$valueNormalized\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Data provider: values with their normalized pairs.
     *
     * @return  array                               Data.
     ************************************************************************/
    public function dataProviderNormalizedValues(): array
    {
        $result = [];

        foreach (SchemeValuesProvider::getValidValues() as $value => $valueNormalized) {
            $result[] = [$value, $valueNormalized];
        }

        return $result;
    }
    /** **********************************************************************
     * Data provider: invalid values.
     *
     * @return  array                               Data.
     ************************************************************************/
    public function dataProviderInvalidValues(): array
    {
        $result = [];

        foreach (SchemeValuesProvider::getInvalidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }
    /** **********************************************************************
     * Data provider: valid values (with their normalized pairs) with invalid values.
     *
     * @return  array                               Data.
     ************************************************************************/
    public function dataProviderValidWithInvalidValues(): array
    {
        $validValues    = $this->dataProviderNormalizedValues();
        $invalidValues  = $this->dataProviderInvalidValues();
        $result         = [];

        foreach ($invalidValues as $data) {
            $result[] = [
                $validValues[0][0],
                $validValues[0][1],
                $data[0]
            ];
        }

        return $result;
    }
}