<?php

/**
 * @file
 * @brief
 *
 * @details
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
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 */
/**
 * @namespace libs.sprayfire.request
 * @brief Contains all classes and interfaces needed to parse the requested URI
 * and manage the HTTP data, both headers and normal GET/POST data, that get passed
 * in each request.
 */

/**
 *
 */
class SprayFireDirectoryTest extends PHPUnit_Framework_TestCase {

    protected $originalAppPath;

    protected $installPath;

    public function setUp() {

        // we are also testing that the subdirectory path will return the correct path if
        // there are no arguments passed
        $this->originalAppPath = \SprayFire\Core\Directory::getAppPath();

        $this->installPath = \dirname(__DIR__);
        $libsPath = $this->installPath . '/tests/mockframework/libs';
        $appPath = $this->installPath . '/tests/mockframework/app';

        \SprayFire\Core\Directory::setInstallPath($this->installPath);
        \SprayFire\Core\Directory::setLibsPath($libsPath);
        \SprayFire\Core\Directory::setAppPath($appPath);

        $expectedAppSub = $this->installPath . '/tests/mockframework/app/TestApp/config';
        $actualAppSub = \SprayFire\Core\Directory::getAppPath('TestApp', 'config');
        $this->assertSame($expectedAppSub, $actualAppSub);
        $this->assertSame($this->installPath, \SprayFire\Core\Directory::getInstallPath());
    }

    public function testLibsPath() {
        $expected = $this->installPath . '/tests/mockframework/libs';
        $actual = \SprayFire\Core\Directory::getLibsPath();
        $this->assertSame($expected, $actual);
    }

    public function testLibsPathPassingListOfArguments() {
        $expected = $this->installPath . '/tests/mockframework/libs/SprayFire/Controller/Components';
        $actual = \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Controller', 'Components');
        $this->assertSame($expected, $actual);
    }

    public function testLibsPathPassingListOfArgumentsWithFileAtEnd() {
        $expected = $this->installPath . '/tests/mockframework/libs/SprayFire/Config/json/configuration-test.json';
        $actual = \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration-test.json');
        $this->assertSame($expected, $actual);
    }

    public function testLibsPathPassingArrayOfArguments() {
        $expected = $this->installPath . '/tests/mockframework/libs/SprayFire/Controller/Components';
        $subDir = array('SprayFire', 'Controller', 'Components');
        $actual = \SprayFire\Core\Directory::getLibsPath($subDir);
        $this->assertSame($expected, $actual);
    }

//    public function testFrameworkSubDirectoryArrayOfArgumentsWithFileAtEnd() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'libs' . DS . 'sprayfire' . DS . 'config' . DS . 'json' . DS . 'configuration-test.json';
//        $subDir = array('config', 'json', 'configuration-test.json');
//        $actual = libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory($subDir);
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testFrameworkSubDirectoryWithNoArguments() {
//        $expected = libs\sprayfire\core\SprayFireDirectory::getFrameworkPath();
//        $actual = libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory();
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testLogsRootDirectory() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'logs';
//        $actual = libs\sprayfire\core\SprayFireDirectory::getLogsPath();
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testLogsSubDirectoryPassingListOfArguments() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'logs' . DS . 'error' . DS . 'database';
//        $actual = libs\sprayfire\core\SprayFireDirectory::getLogsPathSubDirectory('error', 'database');
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testLogsSubDirectoryListOfArgumentsWithFileAtEnd() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'logs' . DS . 'error' . DS . 'database' . DS . 'sql-queries.txt';
//        $actual = libs\sprayfire\core\SprayFireDirectory::getLogsPathSubDirectory('error', 'database', 'sql-queries.txt');
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testLogsSubDirectoryPassingArrayOfArguments() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'logs' . DS . 'error' . DS . 'database';
//        $subDir = array('error', 'database');
//        $actual = libs\sprayfire\core\SprayFireDirectory::getLogsPathSubDirectory($subDir);
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testLogsSubDirectoryArrayOfArgumentsWithFileAtEnd() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'logs' . DS . 'error' . DS . 'database' . DS . 'sql-queries.txt';
//        $subDir = array('error', 'database', 'sql-queries.txt');
//        $actual = libs\sprayfire\core\SprayFireDirectory::getLogsPathSubDirectory($subDir);
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testLogsSubDirectoryWithNoArguments() {
//        $expected = libs\sprayfire\core\SprayFireDirectory::getLogsPath();
//        $actual = libs\sprayfire\core\SprayFireDirectory::getLogsPathSubDirectory();
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testAppPathRootDirectory() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'app';
//        $actual = libs\sprayfire\core\SprayFireDirectory::getAppPath();
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testAppPathSubDirectoryListOfArguments() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'app' . DS . 'model' . DS . 'behaviors';
//        $actual = libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('model', 'behaviors');
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testAppSubDirectoryListOfArgumentsWithFileAtEnd() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'app' . DS . 'config' . DS . 'xml' . DS . 'configuration-test.xml';
//        $actual = libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'xml', 'configuration-test.xml');
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testAppPathSubDirectoryArrayOfArguments() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'app' . DS . 'model' . DS . 'behaviors';
//        $subDir = array('model', 'behaviors');
//        $actual = libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory($subDir);
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testAppSubDirectoryArrayOfArgumentsWithFileAtEnd() {
//        $expected = ROOT_PATH . DS . 'tests' . DS . 'mockframework' . DS . 'app' . DS . 'config' . DS . 'xml' . DS . 'configuration-test.xml';
//        $subDir = array('config', 'xml', 'configuration-test.xml');
//        $actual = libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory($subDir);
//        $this->assertSame($expected, $actual);
//    }
//
//    public function testAppSubDirectoryWithNoArguments() {
//        $expected = libs\sprayfire\core\SprayFireDirectory::getAppPath();
//        $actual = libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory();
//        $this->assertSame($expected, $actual);
//    }
//
//    public function tearDown() {
//        \libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath(ROOT_PATH);
//
//        $this->assertSame($this->originalFrameworkPath, \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory());
//        $this->assertSame($this->originalAppPath, \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory());
//    }

}

// End SprayFireDirectoryTest
