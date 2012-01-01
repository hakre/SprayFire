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

namespace SprayFire\Test\Cases;

/**
 *
 */
class SprayFireRouterTest extends \PHPUnit_Framework_TestCase {

    private $validLogPath;

    private $invalidLogPath;

    public function setUp() {


        $appPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app';
        $logPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/logs';
        \chmod($logPath, 0777);
        $this->validLogPath = $logPath . '/no-errors.txt';
        $this->invalidLogPath = $logPath . '/config/error.txt';
    }

    public function testRootUriRouting() {
        $routesConfigPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/Config/json/routes.json';
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);

        $Router = new \SprayFire\Request\Router\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/';
        $Uri = new \SprayFire\Request\BaseUri($requestedUri, \basename(\SPRAYFIRE_ROOT));

        $defaultController = 'pages';
        $defaultAction = 'index';
        $routedUriString = '/pages/index/';

        $RoutedUri = $Router->getRoutedUri($Uri, \basename(\SPRAYFIRE_ROOT));

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($defaultController, $RoutedUri->getController());
        $this->assertSame($defaultAction, $RoutedUri->getAction());
        $this->assertSame(array(), $RoutedUri->getParameters());

    }

    public function testRoutedUriWithControllerParametersUsingWildCard() {
        $routesConfigPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/Config/json/routes.json';
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);

        $Router = new \SprayFire\Request\Router\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/posts/:tag/:post-title-or-slug';
        $Uri = new \SprayFire\Request\BaseUri($requestedUri, \basename(\SPRAYFIRE_ROOT));

        $expectedController = 'articles';
        $expectedAction = 'view';
        $routedUriString = '/articles/view/tag/post-title-or-slug/';

        $RoutedUri = $Router->getRoutedUri($Uri, \basename(\SPRAYFIRE_ROOT));

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($expectedController, $RoutedUri->getController());
        $this->assertSame($expectedAction, $RoutedUri->getAction());
        $this->assertSame(array('tag', 'post-title-or-slug'), $RoutedUri->getParameters());
    }

    public function testRoutedUriWithControllersParametersUsingSpecific() {
        $routesConfigPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/routes.json';
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);

        $Router = new \SprayFire\Request\Router\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/dogs/train/obedience/teach-your-dog-to-stay';
        $Uri = new \SprayFire\Request\BaseUri($requestedUri, \basename(\SPRAYFIRE_ROOT));

        $expectedController = 'dogs_train_two';
        $expectedAction = 'train_two';
        $routedUriString = '/dogs_train_two/train_two/obedience/teach-your-dog-to-stay/';

        $RoutedUri = $Router->getRoutedUri($Uri);

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($expectedController, $RoutedUri->getController());
        $this->assertSame($expectedAction, $RoutedUri->getAction());
        $this->assertSame(array('obedience', 'teach-your-dog-to-stay'), $RoutedUri->getParameters());
    }

    public function testRoutedUriWithControllersParametersUsingSpecificAndNoControllerSet() {
        $routesConfigPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/routes.json';
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new \SprayFire\logger\FileLogger($LogFile);

        $Router = new \SprayFire\Request\Router\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/dogs/train/obedience/sit/stay/come';
        $Uri = new \SprayFire\Request\BaseUri($requestedUri, \basename(\SPRAYFIRE_ROOT));

        $expectedController = 'dogs';
        $expectedAction = 'train_all';
        $routedUriString = '/dogs/train_all/obedience/sit/stay/come/';

        $RoutedUri = $Router->getRoutedUri($Uri);

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($expectedController, $RoutedUri->getController());
        $this->assertSame($expectedAction, $RoutedUri->getAction());
        $this->assertSame(array('obedience', 'sit', 'stay', 'come'), $RoutedUri->getParameters());
    }

    public function testRoutedUriWithOnlyOneParameter() {
        $routesConfigPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/routes.json';
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);

        $Router = new \SprayFire\Request\Router\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/:param';
        $Uri = new \SprayFire\Request\BaseUri($requestedUri, \basename(\SPRAYFIRE_ROOT));

        $expectedController = 'posts';
        $expectedAction = 'index';
        $routedUriString = '/posts/index/param/';

        $RoutedUri = $Router->getRoutedUri($Uri);

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($expectedController, $RoutedUri->getController());
        $this->assertSame($expectedAction, $RoutedUri->getAction());
        $this->assertSame(array('param'), $RoutedUri->getParameters());
    }

    public function testRoutedUriWithOnlyTwoParameters() {
        $routesConfigPath = \SPRAYFIRE_ROOT .  '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/routes.json';
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);

        $Router = new \SprayFire\Request\Router\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/:param1/:param2';
        $Uri = new \SprayFire\Request\BaseUri($requestedUri, \basename(\SPRAYFIRE_ROOT));

        $expectedController = 'pages';
        $expectedAction = 'display';
        $routedUriString = '/pages/display/param1/param2/';

        $RoutedUri = $Router->getRoutedUri($Uri);

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($expectedController, $RoutedUri->getController());
        $this->assertSame($expectedAction, $RoutedUri->getAction());
        $this->assertSame(array('param1', 'param2'), $RoutedUri->getParameters());
    }

    public function testRoutedUriWithWildCardAndNoDefinedAction() {
        $routesConfigPath = \SPRAYFIRE_ROOT .  '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/routes.json';
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);

        $Router = new \SprayFire\Request\Router\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/songs/:genre/:hip-hop';
        $Uri = new \SprayFire\Request\BaseUri($requestedUri, \basename(\SPRAYFIRE_ROOT));

        $expectedController = 'music';
        $expectedAction = 'index';
        $routedUriString = '/music/index/genre/hip-hop/';

        $RoutedUri = $Router->getRoutedUri($Uri);

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($expectedController, $RoutedUri->getController());
        $this->assertSame($expectedAction, $RoutedUri->getAction());
        $this->assertSame(array('genre', 'hip-hop'), $RoutedUri->getParameters());
    }

    public function testRoutedUriWithInvalidConfiguration() {
        $routesConfigPath = \SPRAYFIRE_ROOT .  '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/invalid-routes.json';
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);


        $LogFile = new \SplFileInfo($this->invalidLogPath);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);
        $Router = new \SprayFire\Request\Router\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/:genre/:hip-hop';
        $Uri = new \SprayFire\Request\BaseUri($requestedUri, \basename(\SPRAYFIRE_ROOT));

        $expectedController = 'pages';
        $expectedAction = 'index';
        $routedUriString = '/pages/index/genre/hip-hop/';

        $RoutedUri = $Router->getRoutedUri($Uri);

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($expectedController, $RoutedUri->getController());
        $this->assertSame($expectedAction, $RoutedUri->getAction());
        $this->assertSame(array('genre', 'hip-hop'), $RoutedUri->getParameters());

        $expectedLogLines = array();
    }

    public function testDefaultControllerAndActionSetInConfigWithSpecificPattern() {
        $routesConfigPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/routes.json';
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new \SprayFire\Logger\FileLogger($LogFile);

        $Router = new \SprayFire\Request\Router\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/dogs/:stay';
        $Uri = new \SprayFire\Request\BaseUri($requestedUri, \basename(\SPRAYFIRE_ROOT));

        $expectedController = 'pages';
        $expectedAction = 'index';
        $routedUriString = '/pages/index/stay/';

        $RoutedUri = $Router->getRoutedUri($Uri);

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($expectedController, $RoutedUri->getController());
        $this->assertSame($expectedAction, $RoutedUri->getAction());
        $this->assertSame(array('stay'), $RoutedUri->getParameters());
    }

    public function tearDown() {
        if (\file_exists($this->validLogPath)) {
            \unlink($this->validLogPath);
        }
        $this->assertFalse(\file_exists($this->validLogPath));

        if (\file_exists($this->invalidLogPath)) {
            \unlink($this->invalidLogPath);
        }
        $this->assertFalse(\file_exists($this->invalidLogPath));
    }


}

// End SprayFireRouterTest
