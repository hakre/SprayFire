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
class SprayFireRouterTest extends PHPUnit_Framework_TestCase {

    private $validLogPath;

    private $controllerParamsWildCard;

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

        $this->validLogPath = libs\sprayfire\core\SprayFireDirectory::getLogsPathSubDirectory('no-errors.txt');
    }

    public function testRootUriRouting() {
        $routesConfigPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'routes.json');
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new libs\sprayfire\config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new libs\sprayfire\logger\FileLogger($LogFile);

        $Router = new \libs\sprayfire\request\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/';
        $Uri = new libs\sprayfire\request\BaseUri($requestedUri);

        $defaultController = 'pages';
        $defaultAction = 'index';
        $routedUriString = '/pages/index/';

        $RoutedUri = $Router->getRoutedUri($Uri);

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($defaultController, $RoutedUri->getController());
        $this->assertSame($defaultAction, $RoutedUri->getAction());
        $this->assertSame(array(), $RoutedUri->getParameters());

    }

    public function testRoutedUriWithControllerParametersUsingWildCard() {
        $routesConfigPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'routes.json');
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new libs\sprayfire\config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new libs\sprayfire\logger\FileLogger($LogFile);

        $Router = new \libs\sprayfire\request\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/posts/:tag/:post-title-or-slug';
        $Uri = new libs\sprayfire\request\BaseUri($requestedUri);

        $expectedController = 'articles';
        $expectedAction = 'view';
        $routedUriString = '/articles/view/tag/post-title-or-slug/';

        $RoutedUri = $Router->getRoutedUri($Uri);

        $this->assertSame($requestedUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUriString, $RoutedUri->getRoutedUri());
        $this->assertSame($expectedController, $RoutedUri->getController());
        $this->assertSame($expectedAction, $RoutedUri->getAction());
        $this->assertSame(array('tag', 'post-title-or-slug'), $RoutedUri->getParameters());
    }

    public function testRoutedUriWithControllersParametersUsingSpecific() {
        $routesConfigPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'routes.json');
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new libs\sprayfire\config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new libs\sprayfire\logger\FileLogger($LogFile);

        $Router = new \libs\sprayfire\request\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/dogs/train/obedience/teach-your-dog-to-stay';
        $Uri = new libs\sprayfire\request\BaseUri($requestedUri);

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
        $routesConfigPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'routes.json');
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new libs\sprayfire\config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new libs\sprayfire\logger\FileLogger($LogFile);

        $Router = new \libs\sprayfire\request\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/dogs/train/obedience/sit/stay/come';
        $Uri = new libs\sprayfire\request\BaseUri($requestedUri);

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
        $routesConfigPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'routes.json');
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new libs\sprayfire\config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new libs\sprayfire\logger\FileLogger($LogFile);

        $Router = new \libs\sprayfire\request\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/:param';
        $Uri = new libs\sprayfire\request\BaseUri($requestedUri);

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
        $routesConfigPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'routes.json');
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new libs\sprayfire\config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new libs\sprayfire\logger\FileLogger($LogFile);

        $Router = new \libs\sprayfire\request\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/:param1/:param2';
        $Uri = new libs\sprayfire\request\BaseUri($requestedUri);

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
        $routesConfigPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'routes.json');
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new libs\sprayfire\config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new libs\sprayfire\logger\FileLogger($LogFile);

        $Router = new \libs\sprayfire\request\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/songs/:genre/:hip-hop';
        $Uri = new libs\sprayfire\request\BaseUri($requestedUri);

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
        $routesConfigPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'invalid-routes.json');
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new libs\sprayfire\config\JsonConfig($RoutesConfigFile);

        $this->controllerParamsWildCard = libs\sprayfire\core\SprayFireDirectory::getLogsPathSubDirectory('config', 'error.txt');
        $LogFile = new \SplFileInfo($this->controllerParamsWildCard);
        $Logger = new libs\sprayfire\logger\FileLogger($LogFile);
        $Router = new \libs\sprayfire\request\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/:genre/:hip-hop';
        $Uri = new libs\sprayfire\request\BaseUri($requestedUri);

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
        $routesConfigPath = \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'routes.json');
        $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
        $RoutesConfig = new libs\sprayfire\config\JsonConfig($RoutesConfigFile);

        $LogFile = new \SplFileInfo($this->validLogPath);
        $Logger = new libs\sprayfire\logger\FileLogger($LogFile);

        $Router = new \libs\sprayfire\request\SprayFireRouter($RoutesConfig, $Logger);

        $requestedUri = '/dogs/:stay';
        $Uri = new libs\sprayfire\request\BaseUri($requestedUri);

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
        \libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath(ROOT_PATH);

        $this->assertSame($this->originalFrameworkPath, \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory());
        $this->assertSame($this->originalAppPath, \libs\sprayfire\core\SprayFireDirectory::getAppPathSubDirectory());

        if (\file_exists($this->validLogPath)) {
            \unlink($this->validLogPath);
        }

        if (\file_exists($this->controllerParamsWildCard)) {
            \unlink($this->controllerParamsWildCard);
        }
    }


}

// End SprayFireRouterTest
