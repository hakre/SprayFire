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
 *
 */
class DirectoryTest extends PHPUnit_Framework_TestCase {

    protected $paths;

    public function setUp() {
        $installPath = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $libsPath = $installPath . '/libs';
        $appPath = $installPath . '/app';
        $logsPath = $installPath . '/logs';
        $webPath = $installPath . '/web';
        $configPath = $installPath . '/config';
        if (!isset($this->paths)) {
            $this->paths = \compact('installPath', 'libsPath', 'appPath', 'logsPath', 'webPath', 'configPath');
        }
        if (!interface_exists('\\SprayFire\\Core\\Object')) {
            include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Object.php';
        }
        if (!class_exists('\\SprayFire\\Core\\CoreObject')) {
            include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/CoreObject.php';
        }
        if (!interface_exists('\\SprayFire\\Core\\PathGenerator')) {
            include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/PathGenerator.php';
        }
        if (!class_exists('\\SprayFire\\Core\\Directory')) {
            include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Directory.php';
        }
    }

    public function testInstallPath() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setInstallPath($this->paths['installPath']);
        $this->assertSame($this->paths['installPath'], $Directory->getInstallPath());
        $this->assertSame($this->paths['installPath'] . '/Test/App', $Directory->getInstallPath('Test', 'App'));
    }

    public function testLibsPath() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setLibsPath($this->paths['libsPath']);
        $this->assertSame($this->paths['libsPath'], $Directory->getLibsPath());
    }

    public function testLibsPathPassingListOfArguments() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setLibsPath($this->paths['libsPath']);
        $expected = $this->paths['libsPath'] . '/SprayFire/Controller/Components';
        $actual = $Directory->getLibsPath('SprayFire', 'Controller', 'Components');
        $this->assertSame($expected, $actual);
    }

    public function testLibsPathPassingListOfArgumentsWithFileAtEnd() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setLibsPath($this->paths['libsPath']);
        $expected = $this->paths['libsPath'] . '/SprayFire/Config/json/configuration-test.json';
        $actual = $Directory->getLibsPath('SprayFire', 'Config', 'json', 'configuration-test.json');
        $this->assertSame($expected, $actual);
    }

    public function testLibsPathPassingArrayOfArguments() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setLibsPath($this->paths['libsPath']);
        $expected = $this->paths['libsPath'] . '/SprayFire/Controller/Components';
        $subDir = array('SprayFire', 'Controller', 'Components');
        $actual = $Directory->getLibsPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testLibsPathPassingArrayOfArgumentsWithFileAtEnd() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setLibsPath($this->paths['libsPath']);
        $expected = $this->paths['libsPath'] . '/SprayFire/Config/json/configuration-test.json';
        $subDir = array('SprayFire', 'Config', 'json', 'configuration-test.json');
        $actual = $Directory->getLibsPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testLogsPath() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setLogsPath($this->paths['logsPath']);
        $expected = $this->paths['logsPath'];
        $actual = $Directory->getLogsPath();
        $this->assertSame($expected, $actual);
    }

    public function testLogsPathPassingListOfArguments() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setLogsPath($this->paths['logsPath']);
        $expected = $this->paths['logsPath'] . '/error/database';
        $actual = $Directory->getLogsPath('error', 'database');
        $this->assertSame($expected, $actual);
    }

    public function testLogsPathPassingListOfArgumentsWithFileAtEnd() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setLogsPath($this->paths['logsPath']);
        $expected = $this->paths['logsPath'] . '/error/database/sql-queries.txt';
        $actual = $Directory->getLogsPath('error', 'database', 'sql-queries.txt');
        $this->assertSame($expected, $actual);
    }

    public function testLogsPathPassingArrayOfArguments() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setLogsPath($this->paths['logsPath']);
        $expected = $this->paths['logsPath'] . '/error/database';
        $subDir = array('error', 'database');
        $actual = $Directory->getLogsPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testLogsPathPassingArrayOfArgumentsWithFileAtEnd() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setLogsPath($this->paths['logsPath']);
        $expected = $this->paths['logsPath'] . '/error/database/sql-queries.txt';
        $subDir = array('error', 'database', 'sql-queries.txt');
        $actual = $Directory->getLogsPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testAppPathRootDirectory() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setAppPath($this->paths['appPath']);
        $expected = $this->paths['appPath'];
        $actual = $Directory->getAppPath();
        $this->assertSame($expected, $actual);
    }

    public function testAppPathPassingListOfArguments() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setAppPath($this->paths['appPath']);
        $expected = $this->paths['appPath'] . '/Model/Behavior';
        $actual = $Directory->getAppPath('Model', 'Behavior');
        $this->assertSame($expected, $actual);
    }

    public function testAppPathPassingListOfArgumentsWithFileAtEnd() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setAppPath($this->paths['appPath']);
        $expected = $this->paths['appPath'] . '/Config/xml/configuration-test.xml';
        $actual = $Directory->getAppPath('Config', 'xml', 'configuration-test.xml');
        $this->assertSame($expected, $actual);
    }

    public function testAppPathPassingArrayOfArguments() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setAppPath($this->paths['appPath']);
        $expected = $this->paths['appPath'] . '/Model/Behavior';
        $subDir = array('Model', 'Behavior');
        $actual = $Directory->getAppPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testAppPathPassingArrayOfArgumentsWithFileAtEnd() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setAppPath($this->paths['appPath']);
        $expected = $this->paths['appPath'] . '/config/xml/configuration-test.xml';
        $subDir = array('config', 'xml', 'configuration-test.xml');
        $actual = $Directory->getAppPath($subDir);
        $this->assertSame($expected, $actual);
    }



    public function testWebPathRootDirectory() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setWebPath($this->paths['webPath']);
        $expected = $this->paths['webPath'];
        $actual = $Directory->getWebPath();
        $this->assertSame($expected, $actual);
    }

    public function testWebPathPassingListOfArguments() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setWebPath($this->paths['webPath']);
        $expected = $this->paths['webPath'] . '/css/head';
        $actual = $Directory->getWebPath('css', 'head');
        $this->assertSame($expected, $actual);
    }

    public function testWebPathPassingListOfArgumentsWithFileAtEnd() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setWebPath($this->paths['webPath']);
        $expected = $this->paths['webPath'] . '/css/head/style.css';
        $actual = $Directory->getWebPath('css', 'head', 'style.css');
        $this->assertSame($expected, $actual);
    }

    public function testWebPathPassingArrayOfArguments() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setWebPath($this->paths['webPath']);
        $expected = $this->paths['webPath'] . '/script/sprayfire_js';
        $subDir = array('script', 'sprayfire_js');
        $actual = $Directory->getWebPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testWebPathPassingArrayOfArgumentsWithFileAtEnd() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setWebPath($this->paths['webPath']);
        $expected = $this->paths['webPath'] . '/script/sprayfire_js/core.js';
        $subDir = array('script', 'sprayfire_js', 'core.js');
        $actual = $Directory->getWebPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testUrlPathRootDirectory() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setInstallPath($this->paths['installPath']);
        $Directory->setWebPath($this->paths['webPath']);
        $expected = '/mockframework/web';
        $actual = $Directory->getUrlPath();
        $this->assertSame($expected, $actual);
    }

    public function testUrlPathPassingListOfArguments() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setInstallPath($this->paths['installPath']);
        $Directory->setWebPath($this->paths['webPath']);
        $expected = '/mockframework/web/css/head';
        $actual = $Directory->getUrlPath('css', 'head');
        $this->assertSame($expected, $actual);
    }

    public function testUrlPathPassingListOfArgumentsWithFileAtEnd() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setInstallPath($this->paths['installPath']);
        $Directory->setWebPath($this->paths['webPath']);
        $expected = '/mockframework/web/css/head/style.css';
        $actual = $Directory->getUrlPath('css', 'head', 'style.css');
        $this->assertSame($expected, $actual);
    }

    public function testUrlPathPassingArrayOfArguments() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setInstallPath($this->paths['installPath']);
        $Directory->setWebPath($this->paths['webPath']);
        $expected = '/mockframework/web/script/sprayfire_js';
        $subDir = array('script', 'sprayfire_js');
        $actual = $Directory->getUrlPath($subDir);
        $this->assertSame($expected, $actual);
    }

    public function testUrlPathPassingArrayOfArgumentsWithFileAtEnd() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setInstallPath($this->paths['installPath']);
        $Directory->setWebPath($this->paths['webPath']);
        $expected = '/mockframework/web/script/sprayfire_js/core.js';
        $subDir = array('script', 'sprayfire_js', 'core.js');
        $actual = $Directory->getUrlPath($subDir);
        $this->assertSame($expected, $actual);
    }

}