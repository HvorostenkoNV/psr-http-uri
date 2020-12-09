<?php
declare(strict_types=1);

namespace HNV\Http\UriTests;

use Throwable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use HNV\Http\UriTests\ValuesProvider\Host as HostValuesProvider;
use HNV\Http\Uri\Uri;

use function spl_object_id;
/** ***********************************************************************************************
 * PSR-7 UriInterface implementation test.
 *
 * Testing working with host values.
 *
 * @package HNV\Psr\Http\Tests\Uri
 * @author  Hvorostenko
 *************************************************************************************************/
class UriHostTest extends TestCase
{
    /** **********************************************************************
     * Test "Uri::withHost" provides new instance of URI.
     *
     * @covers          Uri::withHost
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
        $uriSecond  = $uriFirst->withHost($value);
        $uriThird   = $uriSecond->withHost($value);

        self::assertNotEquals(
            spl_object_id($uriFirst),
            spl_object_id($uriSecond),
            "Action \"Uri->withHost\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            "Caught result is \"SAME INSTANCE\"."
        );
        self::assertNotEquals(
            spl_object_id($uriSecond),
            spl_object_id($uriThird),
            "Action \"Uri->withHost\" returned unexpected result.\n".
            "Expected result is \"NEW INSTANCE\".\n".
            "Caught result is \"SAME INSTANCE\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::getHost" provides valid normalized value.
     *
     * @covers          Uri::getHost
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
        $valueCaught = (new Uri())->withHost($value)->getHost();

        self::assertEquals(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withHost->getHost\" returned unexpected result.\n".
            "Action was called with parameters (value => $value).\n".
            "Expected result is \"$valueNormalized\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::getHost" provides expects value from empty object.
     *
     * @covers  Uri::getHost
     *
     * @return  void
     * @throws  Throwable
     ************************************************************************/
    public function testGetValueOnEmptyObject(): void
    {
        $valueCaught = (new Uri())->getHost();

        self::assertEquals(
            '',
            $valueCaught,
            "Action \"Uri->getHost\" returned unexpected result.\n".
            "Expected result is \"empty string\" from empty object.\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::withHost" clears value on setting empty string.
     *
     * @covers          Uri::withHost
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
            ->withHost($value)
            ->withHost('')
            ->getHost();

        self::assertEquals(
            '',
            $valueCaught,
            "Action \"Uri->withHost->withHost->getHost\" returned unexpected result.\n".
            "Action was called with parameters (value => $value, value => \"empty string\").\n".
            "Expected result is \"empty string\".\n".
            "Caught result is \"$valueCaught\"."
        );
    }
    /** **********************************************************************
     * Test "Uri::withHost" throws exception with invalid argument.
     *
     * @covers          Uri::withHost
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

        (new Uri())->withHost($value);

        self::fail(
            "Action \"Uri->withHost\" threw no expected exception.\n".
            "Action was called with parameters (value => $value).\n".
            "Expects \"InvalidArgumentException\" exception.\n".
            'Caught no exception.'
        );
    }
    /** **********************************************************************
     * Test "Uri::withHost" saves previous value with setting new invalid value.
     *
     * @covers          Uri::withHost
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
        $uri = (new Uri())->withHost($value);

        try {
            $uri->withHost($invalidValue);
        } catch (InvalidArgumentException) {

        }

        $valueCaught = $uri->getHost();

        self::assertEquals(
            $valueNormalized,
            $valueCaught,
            "Action \"Uri->withHost->withHost->getHost\" returned unexpected result.\n".
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

        foreach (HostValuesProvider::getValidValues() as $value => $valueNormalized) {
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

        foreach (HostValuesProvider::getInvalidValues() as $value) {
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