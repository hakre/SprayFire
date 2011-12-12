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
class FrameworkConfigTest extends PHPUnit_Framework_TestCase {

    private $FrameworkConfig;

    private $originalFrameworkPath;

    public function setUp() {
        $rootPath = ROOT_PATH . DS . 'tests' . DS . 'mockframework';
        $this->originalFrameworkPath = libs\sprayfire\core\SprayFireDirectory::getFrameworkPath();
        libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath($rootPath);

        $frameworkConfigFile = libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory('config', 'xml');
        $frameworkConfigFile .= DS . 'test-framework-config.xml';

        $exceptionThrown = false;
        try {
            $this->FrameworkConfig = new libs\sprayfire\config\FrameworkConfig();
            $this->FrameworkConfig->importConfig($frameworkConfigFile);
        } catch (\InvalidArgumentException $InvalArgExc) {
            error_log($InvalArgExc->getMessage());
            $exceptionThrown = true;
        }

        $this->assertFalse($exceptionThrown);
    }

    public function testFrameworkConfig() {
        $this->assertTrue(isset($this->FrameworkConfig));

        $shouldBeNull = $this->FrameworkConfig->nonexist;
        $this->assertNull($shouldBeNull);

        $isAppConfigFileSet = isset($this->FrameworkConfig->appConfigFile);
        $this->assertTrue($isAppConfigFileSet);

        $isNonExistSet = isset($this->FrameworkConfig->nonexist);
        $this->assertFalse($isNonExistSet);

        $expectedVersion = '4.10.7-gold-rc';
        $actualVersion = $this->FrameworkConfig->version;
        $this->assertSame($expectedVersion, $actualVersion);

        $expectedAppConfigFile = 'test-app-config.xml';
        $actualAppConfigFile = $this->FrameworkConfig->appConfigFile;
        $this->assertSame($expectedAppConfigFile, $actualAppConfigFile);

        $expectedRoutesConfigFile = 'test-routes.xml';
        $actualRoutesConfigFile = $this->FrameworkConfig->routesConfigFile;
        $this->assertSame($expectedRoutesConfigFile, $actualRoutesConfigFile);

        $expectedRoutesController = 'test_pages';
        $actualRoutesController = $this->FrameworkConfig->routesDefaultController;
        $this->assertSame($expectedRoutesController, $actualRoutesController);

        $expectedRoutesAction = 'routing_action';
        $actualRoutesAction = $this->FrameworkConfig->routesDefaultAction;
        $this->assertSame($expectedRoutesAction, $actualRoutesAction);

        $expectedErrorClass = 'TestErrorHandler';
        $actualErrorClass = $this->FrameworkConfig->errorHandlingClass;
        $this->assertSame($expectedErrorClass, $actualErrorClass);

        $expectedErrorAction = 'error_action';
        $actualErrorAction = $this->FrameworkConfig->errorHandlingAction;
        $this->assertSame($expectedErrorAction, $actualErrorAction);

        $expectedExceptionClass = 'TestExceptionHandler';
        $actualExceptionClass = $this->FrameworkConfig->exceptionHandlingClass;
        $this->assertSame($expectedExceptionClass, $actualExceptionClass);

        $expectedExceptionAction = 'exception_action';
        $actualExceptionAction = $this->FrameworkConfig->exceptionHandlingAction;
        $this->assertSame($expectedExceptionAction, $actualExceptionAction);

        $expectedTimezone = 'America/New_York';
        $actualTimezone = $this->FrameworkConfig->timezone;
        $this->assertSame($expectedTimezone, $actualTimezone);

        $expectedCharset = 'UTF-8';
        $actualCharset = $this->FrameworkConfig->charset;
        $this->assertSame($expectedCharset, $actualCharset);

        $expectedShortOpenTags = 1;
        $actualShortOpenTags = $this->FrameworkConfig->shortOpenTags;
        $this->assertSame($expectedShortOpenTags, $actualShortOpenTags);

        $expectedExposePhp = 0;
        $actualExposePhp = $this->FrameworkConfig->exposePhp;
        $this->assertSame($expectedExposePhp, $actualExposePhp);

        $expectedAspTags = 1;
        $actualAspTags = $this->FrameworkConfig->aspTags;
        $this->assertSame($expectedAspTags, $actualAspTags);

    }

    /**
     * @expectedException \libs\sprayfire\exceptions\UnsupportedOperationException
     */
    public function testIsConfigMutableWithSet() {
        $this->assertTrue(isset($this->FrameworkConfig->version));
        $this->FrameworkConfig->version = 'something else';
    }

    /**
     * @expectedException \libs\sprayfire\exceptions\UnsupportedOperationException
     */
    public function testIsConfigMutableWithUnset() {
        $this->assertTrue(isset($this->FrameworkConfig->version));
        unset($this->FrameworkConfig->version);
    }

    public function tearDown() {
        \libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath(ROOT_PATH);
        $expectedFramework = $this->originalFrameworkPath;
        $actualFramework = \libs\sprayfire\core\SprayFireDirectory::getFrameworkPath();
        $this->assertSame($expectedFramework, $actualFramework);
    }

}

// End FrameworkConfigTest
