<?php

/**
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.2.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

$overloadablePath = '../libs/sprayfire/interfaces/Overloadable.php';
$coreObjectPath = '../libs/sprayfire/core/CoreObject.php';
$coreConfigurationPath = '../libs/sprayfire/core/CoreConfiguration.php';
$baseConfigurationPath = '../libs/sprayfire/core/BaseConfig.php';
$configurationPath = '../libs/sprayfire/interfaces/Configuration.php';

include $overloadablePath;
include $coreObjectPath;
include $configurationPath;
include $baseConfigurationPath;
include $coreConfigurationPath;


/**
 * Will test the SF_BaseConfig and SF_CoreConfiguration objects to assure they
 * can properly read/write mixed key/value pairs.
 *
 * This method passing means that all configuration objects extending SF_BaseConfig
 * will pass the same methods as all implementing code is stored in that object.
 */
class CoreConfigurationTest extends PHPUnit_Framework_TestCase {

    /**
     * Will create the necessary SF_CoreConfiguration object, write several values
     * into the object with known keys, read those values and assert that the read
     * value is the same as the written value.
     */
    public function testWriteAndRead() {

        echo 'Testing the BaseConfig, through CoreConfiguration, to read/write mixed key/value pairs:';
        echo '<br />';

        $TestObject = new CoreConfiguration();

        $TestObject->write('testOne', '1');
        $TestObject->write('testTwo', array('one' => '2'));
        $TestObject->write('testThree', $this);

        $actualTestOne = $TestObject->read('testOne');
        $actualTestTwo = $TestObject->read('testTwo');
        $actualTestThree = $TestObject->read('testThree');
        $actualTestFour = $TestObject->read('testFour');

        $expectedTestOne = '1';
        $expectedTestTwo = array('one' => '2');
        $expectedTestThree = $this;
        $expectedTestFour = NULL;

        $failedAssertions = array();
        try {
            $this->assertEquals($expectedTestOne, $actualTestOne);
        } catch (PHPUnit_Framework_ExpectationFailedException $ExpectationException) {
            $failedAssertions['test_one_read/write'] = array();
            $failedAssertions['test_one_read/write']['details'] = $ExpectationException->getMessage();
            $failedAssertions['test_one_read/write']['trace'] = $ExpectationException->getTrace();
        }

        try {
            $this->assertEquals($expectedTestTwo, $actualTestTwo);
        } catch (PHPUnit_Framework_ExpectationFailedException $ExpectationException) {
            $failedAssertions['test_two_read/write'] = array();
            $failedAssertions['test_two_read/write']['details'] = $ExpectationException->getMessage();
            $failedAssertions['test_two_read/write']['trace'] = $ExpectationException->getTrace();
        }

        try {
            $this->assertEquals($expectedTestThree, $actualTestThree);
        } catch (PHPUnit_Framework_ExpectationFailedException $ExpectationException) {
            $failedAssertions['test_three_read/write'] = array();
            $failedAssertions['test_three_read/write']['details'] = $ExpectationException->getMessage();
            $failedAssertions['test_three_read/write']['trace'] = $ExpectationException->getTrace();
        }

        try {
            $this->assertEquals($expectedTestFour, $actualTestFour);
        } catch (PHPUnit_Framework_ExpectationFailedException $ExpectationException) {
            $failedAssertions['test_four_read/write'] = array();
            $failedAssertions['test_four_read/write']['details'] = $ExpectationException->getMessage();
            $failedAssertions['test_four_read/write']['trace'] = $ExpectationException->getTrace();
        }

        if (empty($failedAssertions)) {
            echo 'The tests passed!';
        } else {
            echo 'The tests FAILED!';
            echo '<pre>';
            echo var_dump($failedAssertions);
            echo '</pre>';
        }

    }

}

// End SF_CoreConfigurationTest
