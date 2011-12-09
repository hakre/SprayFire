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
class FrameworkBootstrapTest extends PHPUnit_Framework_TestCase {

    private $originalFrameworkPath;

    public function setUp() {
        $this->originalFrameworkPath = \libs\sprayfire\core\SprayFireDirectory::getFrameworkPath();
        $testFrameworkRoot = ROOT_PATH . DS . 'tests'. DS . 'mockframework';
        \libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath($testFrameworkRoot);
    }

    public function testFrameworkBootstrapRunningAppBootstraps() {

        $TestCoreConfiguration = new \tests\mockframework\app\config\TestCoreConfiguration();
        $TestCoreConfiguration->write('TestBootstrap', false);

        $expectedAppPath = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'app' . DS . 'bootstrap';
        $actualAppPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('bootstrap');
        $this->assertSame($expectedAppPath, $actualAppPath);

        $FrameworkBootstrap = new \libs\sprayfire\core\FrameworkBootstrap($TestCoreConfiguration);
        $FrameworkBootstrap->runBootstrap();

        $this->assertTrue($TestCoreConfiguration->read('TestBootstrap'));
    }

    public function testSprayFireDirectory() {
        $expectedAppPath = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'app';
        $actualAppPath = \libs\sprayfire\core\SprayFireDirectory::getAppPath();
        $this->assertSame($expectedAppPath, $actualAppPath);

        $expectedFrameworkPath = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'libs' . DS . 'sprayfire';
        $actualFrameworkPath = \libs\sprayfire\core\SprayFireDirectory::getFrameworkPath();
        $this->assertSame($expectedFrameworkPath, $actualFrameworkPath);

        $expectedFrameworkSubDirectoryOneDeep = $expectedFrameworkPath . DS . 'core';
        $actualFrameWorkSubDirectoryOneDeep = \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory('core');
        $this->assertSame($expectedFrameworkSubDirectoryOneDeep, $actualFrameWorkSubDirectoryOneDeep);
    }

    public function tearDown() {
        \libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath(ROOT_PATH);
        $expectedFramework = $this->originalFrameworkPath;
        $actualFramework = \libs\sprayfire\core\SprayFireDirectory::getFrameworkPath();
        $this->assertSame($expectedFramework, $actualFramework);
    }

}

// End FrameworkBootstrapTest
