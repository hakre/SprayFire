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

$coreObjectPath = '../libs/sprayfire/core/CoreObject.php';
$coreConfigurationPath = '../libs/sprayfire/core/CoreConfiguration.php';
$baseConfigurationPath = '../libs/sprayfire/core/BaseConfig.php';
$configurationPath = '../libs/sprayfire/interfaces/Configuration.php';

if (!class_exists('CoreObject')) {
    include $coreObjectPath;
}

if (!interface_exists('Configuration')) {
    include $configurationPath;
}

if (!class_exists('BaseConfig')) {
    include $baseConfigurationPath;
}

if (!class_exists('CoreConfiguration')) {
    include $coreConfigurationPath;
}

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

        $this->assertEquals($expectedTestOne, $actualTestOne);
        $this->assertEquals($expectedTestTwo, $actualTestTwo);
        $this->assertEquals($expectedTestThree, $actualTestThree);
        $this->assertEquals($expectedTestFour, $actualTestFour);

    }

}

// End SF_CoreConfigurationTest
