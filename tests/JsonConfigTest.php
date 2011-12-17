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

/**
 *
 */
class JsonConfigTest extends PHPUnit_Framework_TestCase {

    private $originalFrameworkPath;

    private $originalAppPath;

    public function setUp() {

        // we are also testing that the subdirectory path will return the correct path if
        // there are no arguments passed
        $this->originalFrameworkPath = \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory();
        $this->originalAppPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory();

        $newRootPath = ROOT_PATH . DS . 'tests' . DS . 'mockframework';
        \libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath($newRootPath);

        $expectedFrameworkSub = ROOT_PATH . DS . 'tests' . DS .'mockframework' . DS . 'app' . DS . 'config';
        $actualFrameworkSub = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config');
        $this->assertSame($expectedFrameworkSub, $actualFrameworkSub);
    }

    public function testJsonConfigObject() {
        $File = new SplFileInfo(\libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'test-config.json'));
        $Config = new libs\sprayfire\config\JsonConfig($File);

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
        } catch (\libs\sprayfire\exceptions\UnsupportedOperationException $UnsupportedOp) {
            $exceptionThrownWhenSet = true;
        }

        $this->assertTrue($exceptionThrownWhenSet);

        $exceptionThrownWhenUnset = false;
        try {
            unset($Config->app->version);
        } catch (\libs\sprayfire\exceptions\UnsupportedOperationException $UnsupportedOp) {
            $exceptionThrownWhenUnset = true;
        }

        $this->assertTrue($exceptionThrownWhenUnset);

        $expectedAppVersion = '0.0.1-beta';
        $actualAppVersion = $Config->app->version;
        $this->assertSame($expectedAppVersion, $actualAppVersion);

        $expectedToString = 'libs\sprayfire\config\JsonConfig::ROOT_PATH/tests/mockframework/app/config/test-config.json';
        $actualToString = $Config->__toString();
        $this->assertSame($expectedToString, $actualToString);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonExistentFile() {
        $File = new SplFileInfo('this/file/path/does/not/exist/config.json');
        $Config = new libs\sprayfire\config\JsonConfig($File);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidJsonFile() {
        $File = new SplFileInfo(\libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory('config', 'test-invalid-config.json'));
        $Config = new libs\sprayfire\config\JsonConfig($File);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidChildReturn() {
        $File = new SplFileInfo(\libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'test-config.json'));
        $Config = new CrappyJsonConfig($File);
    }

    public function tearDown() {
        \libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath(ROOT_PATH);

        $this->assertSame($this->originalFrameworkPath, \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory());
        $this->assertSame($this->originalAppPath, \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory());
    }

}

class CrappyJsonConfig extends libs\sprayfire\config\JsonConfig {

    protected function convertDataDeep(array $data) {
        return null;
    }
}

// End JsonConfigTest
