<?php

/**
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.3.
 *
 * SprayFire makes use of namespaces, a custom-built ORM layer, a completely
 * object oriented approach and minimal invasiveness so you can make the framework
 * do what YOU want to do.  Some things we take seriously over here at SprayFire
 * includes clean, readable source, completely unit tested implementations and
 * not polluting the global scope.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

if (!interface_exists('\\SprayFire\\Core\\Object')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Object.php';
}
if (!class_exists('\\SprayFire\\Core\\CoreObject')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/CoreObject.php';
}
if (!interface_exists('\\SprayFire\\Core\\Structure\\Overloadable')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Structure/Overloadable.php';
}
if (!class_exists('\\SprayFire\\Core\\Structure\\DataStorage')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Structure/DataStorage.php';
}
if (!class_exists('\\SprayFire\\Core\\Structure\\ImmutableStorage')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Structure/ImmutableStorage.php';
}
if (!interface_exists('\\SprayFire\\Config\\Configuration')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Config/Configuration.php';
}
if (!class_exists('\\SprayFire\\Config\\JsonConfig')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Config/JsonConfig.php';
}
if (!class_exists('\\SprayFire\\Exception\\UnsupportedOperationException')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Exception/UnsupportedOperationException.php';
}
if (!class_exists('CrappyJsonConfig')) {
    include \SPRAYFIRE_ROOT . '/tests/helpers/CrappyJsonConfig.php';
}

/**
 *
 */
class JsonConfigTest extends PHPUnit_Framework_TestCase {

    public function testJsonConfigObject() {
        $filePath = \SPRAYFIRE_ROOT . '/tests/mockframework/app/TestApp/config/json/test-config.json';
        $File = new SplFileInfo($filePath);
        $Config = new \SprayFire\Config\JsonConfig($File);

        $expectedNoExist = null;
        $actualNoExist = $Config->{'no-exist'};
        $this->assertSame($expectedNoExist, $actualNoExist);

        $noExistIsSet = isset($Config['no-exist']);
        $this->assertFalse($noExistIsSet);

        $doesExistIsSet = isset($Config->app->version);
        $this->assertTrue($doesExistIsSet);

        $expectedFrameworkVersion = '0.1.0-gold-rc';
        $actualFrameworkVersion = $Config->framework['version'];
        $this->assertSame($expectedFrameworkVersion, $actualFrameworkVersion);

        $exceptionThrownWhenSet = false;
        try {
            $Config->app->version = 'some new version';
        } catch (\SprayFire\Exception\UnsupportedOperationException $UnsupportedOp) {
            $exceptionThrownWhenSet = true;
        }

        $this->assertTrue($exceptionThrownWhenSet);

        $exceptionThrownWhenUnset = false;
        try {
            unset($Config->app->version);
        } catch (\SprayFire\Exception\UnsupportedOperationException $UnsupportedOp) {
            $exceptionThrownWhenUnset = true;
        }

        $this->assertTrue($exceptionThrownWhenUnset);

        $expectedAppVersion = '0.0.1-beta';
        $actualAppVersion = $Config->app->version;
        $this->assertSame($expectedAppVersion, $actualAppVersion);

        $expectedToString = 'SprayFire\Config\JsonConfig::' . \SPRAYFIRE_ROOT . '/tests/mockframework/app/TestApp/config/json/test-config.json';
        $actualToString = $Config->__toString();
        $this->assertSame($expectedToString, $actualToString);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonExistentFile() {
        $File = new \SplFileInfo('this/file/path/does/not/exist/config.json');
        $Config = new \SprayFire\Config\JsonConfig($File);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidJsonFile() {
        $filePath = \SPRAYFIRE_ROOT . '/tests/mockframework/libs/SprayFire/Config/json/test-invalid-config.json';
        $File = new \SplFileInfo($filePath);
        $Config = new \SprayFire\Config\JsonConfig($File);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testCrappyExtension() {
        $File = new SplFileInfo(\SPRAYFIRE_ROOT . '/tests/mockframework/app/TestApp/config/json/test-config.json');
        $Config = new CrappyJsonConfig($File);
    }

}

// End JsonConfigTest
