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
class AppConfigTest extends PHPUnit_Framework_TestCase {

    private $AppConfig;

    private $originalFrameworkPath;

    /**
     *
     */
    public function setUp() {
        $rootPath = ROOT_PATH . DS . 'tests' . DS . 'mockframework';
        $this->originalFrameworkPath = libs\sprayfire\core\SprayFireDirectory::getFrameworkPath();
        libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath($rootPath);

        $appConfigFile = libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'xml');
        $appConfigFile .= DS . 'test-app-config.xml';

        $exceptionThrown = false;
        $message = '';
        try {
            $this->AppConfig = new app\config\AppConfig();
            $this->AppConfig->importConfig($appConfigFile);
        } catch (\InvalidArgumentException $InvalArgExc) {
            error_log($InvalArgExc->getMessage());
            $message = $InvalArgExc->getMessage();
            $exceptionThrown = true;
        }

        $this->assertFalse($exceptionThrown, $message);
    }

    public function testAppConfigValues() {

        $expectedVersion = '5.10.15-beta';
        $actualVersion = $this->AppConfig->version;
        $this->assertSame($expectedVersion, $actualVersion);

        $expectedDevelopmentMode = 'off';
        $actualDevelopmentMode = $this->AppConfig->developmentMode;
        $this->assertSame($expectedDevelopmentMode, $actualDevelopmentMode);

        $expectedRoutingController = 'test_app_route';
        $actualRoutingController = $this->AppConfig->routingDefaultController;
        $this->assertSame($expectedRoutingController, $actualRoutingController);

        $expctedRoutingAction = 'test_app_action';
        $actualRoutingAction = $this->AppConfig->routingDefaultAction;
        $this->assertSame($expctedRoutingAction, $actualRoutingAction);

        $expectedErrorHandlingClass = '\\app\\core\\error\\TestAppError';
        $actualErrorHandlingClass = $this->AppConfig->errorHandlingClass;
        $this->assertSame($expectedErrorHandlingClass, $actualErrorHandlingClass);

        $expectedErrorHandlingAction = 'test_app_error_action';
        $actualErrorHandlingAction = $this->AppConfig->errorHandlingAction;
        $this->assertSame($expectedErrorHandlingAction, $actualErrorHandlingAction);

        $expectedExceptionHandlingClass = '\\app\\core\\exception\\TestAppException';
        $actualExceptionHandlingClass = $this->AppConfig->exceptionHandlingClass;
        $this->assertSame($expectedExceptionHandlingClass, $actualExceptionHandlingClass);

        $expectedExceptionHandlingAction = 'test_app_exception';
        $actualExceptionHandlingAction = $this->AppConfig->exceptionHandlingAction;
        $this->assertSame($expectedExceptionHandlingAction, $actualExceptionHandlingAction);

        $expectedTimezone = 'America/New_York';
        $actualTimezone = $this->AppConfig->timezone;
        $this->assertSame($expectedTimezone, $actualTimezone);

        $expectedCharset = 'UTF-8';
        $actualCharset = $this->AppConfig->charset;
        $this->assertSame($expectedCharset, $actualCharset);

        $expectedShortOpenTags = 0;
        $actualShortOpenTags = $this->AppConfig->shortOpenTags;
        $this->assertSame($expectedShortOpenTags, $actualShortOpenTags);

        $expectedExposePhp = 0;
        $actualExposePhp = $this->AppConfig->exposePhp;
        $this->assertSame($expectedExposePhp, $actualExposePhp);

        $expectedAspTags = 1;
        $actualAspTags = $this->AppConfig->aspTags;
        $this->assertSame($expectedAspTags, $actualAspTags);

        $expectedDisplayErrors = 0;
        $actualDisplayErrors = $this->AppConfig->displayErrors;
        $this->assertSame($expectedDisplayErrors, $actualDisplayErrors);

        $expectedDisplayStartupErrors = 0;
        $actualDisplayStartupErrors = $this->AppConfig->displayStartupErrors;
        $this->assertSame($expectedDisplayStartupErrors, $actualDisplayStartupErrors);

        $expectedErrorReporting = 'E_ALL & E_STRICT';
        $actualErrorReporting = $this->AppConfig->errorReporting;
        $this->assertSame($expectedErrorReporting, $actualErrorReporting);

    }

    public function testDevModeOnConfig() {
        // We will only be testing the production ini settings in this test
        $exceptionThrown = false;
        $DevModeConfig = new app\config\AppConfig();
        $devModeConfigPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'xml', 'test-app-config-dev-on.xml');
        $errorMessage = '';
        try {
            $DevModeConfig->importConfig($devModeConfigPath);
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
            $errorMessage = $InvalArgExc->getMessage();
        }

        $this->assertFalse($exceptionThrown, $errorMessage);

        $expectedDisplayErrors = 1;
        $actualDisplayErrors = $DevModeConfig->displayErrors;
        $this->assertSame($expectedDisplayErrors, $actualDisplayErrors);

        $expectedDisplayStartupErrors = 1;
        $actualDisplayStartupErrors = $DevModeConfig->displayStartupErrors;
        $this->assertSame($expectedDisplayStartupErrors, $actualDisplayStartupErrors);

        $expectedErrorReporting = 'E_ALL ^ E_STRICT';
        $actualErrorReporting = $DevModeConfig->errorReporting;
        $this->assertSame($expectedErrorReporting, $actualErrorReporting);
    }

    /**
     *
     */
    public function testNonExistingFile() {
        $filePath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory();
        $filePath .= DS . 'noexist.xml';
        $Config = new app\config\AppConfig();
        $thrown = false;
        $message = '';
        try {
            $Config->importConfig($filePath);
        } catch (\InvalidArgumentException $Exc) {
            $thrown = true;
            $message = $Exc->getMessage();
        }
        $this->assertTrue($thrown, $message);
    }

    public function testInvalidFormattedFile() {
        $filePath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config','xml','test-app-config-invalid.xml');
        $Config = new app\config\AppConfig();
        $thrown = false;
        try {
            $Config->importConfig($filePath);
        } catch (\InvalidArgumentException $InvalArgExc) {
            $thrown = true;
        }
        $this->assertTrue($thrown);
    }

    public function tearDown() {
        \libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath(ROOT_PATH);
        $expectedFramework = $this->originalFrameworkPath;
        $actualFramework = \libs\sprayfire\core\SprayFireDirectory::getFrameworkPath();
        $this->assertSame($expectedFramework, $actualFramework);
    }

}

// End AppConfigTest
