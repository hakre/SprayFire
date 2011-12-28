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
class DirectoryTest extends PHPUnit_Framework_TestCase {

    protected $installPath;

    public function setUp() {

        $this->installPath = \dirname(__DIR__);
        $libsPath = $this->installPath . '/tests/mockframework/libs';
        $appPath = $this->installPath . '/tests/mockframework/app';
        $logsPath = $this->installPath . '/tests/mockframework/logs';
        $webPath = $this->installPath . '/tests/mockframework/web';

        \SprayFire\Core\Directory::setInstallPath($this->installPath);
        \SprayFire\Core\Directory::setLibsPath($libsPath);
        \SprayFire\Core\Directory::setAppPath($appPath);
        \SprayFire\Core\Directory::setLogsPath($logsPath);
        \SprayFire\Core\Directory::setWebPath($webPath);

        $expectedAppSub = $this->installPath . '/tests/mockframework/app/TestApp/config';
        $actualAppSub = \SprayFire\Core\Directory::getAppPath('TestApp', 'config');
        $this->assertSame($expectedAppSub, $actualAppSub);
    }

    public function testInstallPath() {
        $this->assertSame($this->installPath, \SprayFire\Core\Directory::getInstallPath());
        $this->assertSame($this->installPath . '/Test/App', \SprayFire\Core\Directory::getInstallPath('Test', 'App'));
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

    public function testLibsPathPassingArrayOfArgumentsWithFileAtEnd() {
        $expected = $this->installPath . '/tests/mockframework/libs/SprayFire/Config/json/configuration-test.json';
        $subDir = array('SprayFire', 'Config', 'json', 'configuration-test.json');
        $actual = \SprayFire\Core\Directory::getLibsPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testLogsPath() {
        $expected = $this->installPath . '/tests/mockframework/logs';
        $actual = \SprayFire\Core\Directory::getLogsPath();
        $this->assertSame($expected, $actual);
    }

    public function testLogsPathPassingListOfArguments() {
        $expected = $this->installPath . '/tests/mockframework/logs/error/database';
        $actual = \SprayFire\Core\Directory::getLogsPath('error', 'database');
        $this->assertSame($expected, $actual);
    }

    public function testLogsPathPassingListOfArgumentsWithFileAtEnd() {
        $expected = $this->installPath . '/tests/mockframework/logs/error/database/sql-queries.txt';
        $actual = \SprayFire\Core\Directory::getLogsPath('error', 'database', 'sql-queries.txt');
        $this->assertSame($expected, $actual);
    }

    public function testLogsPathPassingArrayOfArguments() {
        $expected = $this->installPath . '/tests/mockframework/logs/error/database';
        $subDir = array('error', 'database');
        $actual = \SprayFire\Core\Directory::getLogsPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testLogsPathPassingArrayOfArgumentsWithFileAtEnd() {
        $expected = $this->installPath . '/tests/mockframework/logs/error/database/sql-queries.txt';
        $subDir = array('error', 'database', 'sql-queries.txt');
        $actual = \SprayFire\Core\Directory::getLogsPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testAppPathRootDirectory() {
        $expected = $this->installPath . '/tests/mockframework/app';
        $actual = \SprayFire\Core\Directory::getAppPath();
        $this->assertSame($expected, $actual);
    }

    public function testAppPathPassingListOfArguments() {
        $expected = $this->installPath . '/tests/mockframework/app/Model/Behavior';
        $actual = \SprayFire\Core\Directory::getAppPath('Model', 'Behavior');
        $this->assertSame($expected, $actual);
    }

    public function testAppPathPassingListOfArgumentsWithFileAtEnd() {
        $expected = $this->installPath . '/tests/mockframework/app/Config/xml/configuration-test.xml';
        $actual = \SprayFire\Core\Directory::getAppPath('Config', 'xml', 'configuration-test.xml');
        $this->assertSame($expected, $actual);
    }

    public function testAppPathPassingArrayOfArguments() {
        $expected = $this->installPath . '/tests/mockframework/app/Model/Behavior';
        $subDir = array('Model', 'Behavior');
        $actual = \SprayFire\Core\Directory::getAppPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testAppPathPassingArrayOfArgumentsWithFileAtEnd() {
        $expected = $this->installPath . '/tests/mockframework/app/config/xml/configuration-test.xml';
        $subDir = array('config', 'xml', 'configuration-test.xml');
        $actual = \SprayFire\Core\Directory::getAppPath($subDir);
        $this->assertSame($expected, $actual);
    }



    public function testWebPathRootDirectory() {
        $expected = $this->installPath . '/tests/mockframework/web';
        $actual = \SprayFire\Core\Directory::getWebPath();
        $this->assertSame($expected, $actual);
    }

    public function testWebPathPassingListOfArguments() {
        $expected = $this->installPath . '/tests/mockframework/web/css/head';
        $actual = \SprayFire\Core\Directory::getWebPath('css', 'head');
        $this->assertSame($expected, $actual);
    }

    public function testWebPathPassingListOfArgumentsWithFileAtEnd() {
        $expected = $this->installPath . '/tests/mockframework/web/css/head/style.css';
        $actual = \SprayFire\Core\Directory::getWebPath('css', 'head', 'style.css');
        $this->assertSame($expected, $actual);
    }

    public function testWebPathPassingArrayOfArguments() {
        $expected = $this->installPath . '/tests/mockframework/web/script/sprayfire_js';
        $subDir = array('script', 'sprayfire_js');
        $actual = \SprayFire\Core\Directory::getWebPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testWebPathPassingArrayOfArgumentsWithFileAtEnd() {
        $expected = $this->installPath . '/tests/mockframework/web/script/sprayfire_js/core.js';
        $subDir = array('script', 'sprayfire_js', 'core.js');
        $actual = \SprayFire\Core\Directory::getWebPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function tearDown() {
        \SprayFire\Core\Directory::setInstallPath(null);
        \SprayFire\Core\Directory::setLibsPath(null);
        \SprayFire\Core\Directory::setAppPath(null);
        \SprayFire\Core\Directory::setLogsPath(null);
        \SprayFire\Core\Directory::setWebPath(null);

        $this->assertNull(\SprayFire\Core\Directory::getInstallPath());
        $this->assertNull(\SprayFire\Core\Directory::getLibsPath());
        $this->assertNull(\SprayFire\Core\Directory::getAppPath());
        $this->assertNull(\SprayFire\Core\Directory::getLogsPath());
        $this->assertNull(\SprayFire\Core\Directory::getWebPath());
    }

}