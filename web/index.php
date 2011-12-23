<?php

   /**
    * SprayFire is a custom built framework intended to ease the development
    * of websites with PHP 5.3.
    *
    * SprayFire is released under the Open-Source Initiative MIT license.
    *
    * @author Charles Sprayberry <cspray at gmail dot com>
    * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
    * @copyright Copyright (c) 2011, Charles Sprayberry
    */

    use libs\sprayfire\core\SprayFireDirectory,
        libs\sprayfire\core\ClassLoader;

   /**
    * @var DS A short-alias to DIRECTORY_SEPARATOR
    */
    defined('DS') or define('DS', DIRECTORY_SEPARATOR);

    /**
     * @var ROOT_PATH The appliaction's installation path
     */
    defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));

    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // The below variables can be changed and manipulated to adjust the implementation
    // details of the framework's initialization process.
    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    /**
     * @var $ClassLoaderName The completely namespaced class that should be used
     * as the primary framework autoloader.
     */
    $ClassLoaderName = '\\libs\\sprayfire\\core\\ClassLoader';

    /**
     * @var $primaryConfigPath An array representing a sub directory list to the
     *      primary configuration file.
     */
    $primaryConfigPath = array('config', 'json', 'configuration.json');

    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // Ends the variables that allow for the changing of SprayFire implementation
    //
    // PLEASE DO NOT ALTER CODE BELOW THIS LINE!  DO SO AT YOUR OWN RISK!
    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    include ROOT_PATH . DS . 'libs' . DS . 'sprayfire' . DS . 'core' . DS . 'SprayFireDirectory.php';

    SprayFireDirectory::setRootInstallationPath(ROOT_PATH);

    include SprayFireDirectory::getFrameworkPathSubDirectory('core', 'Object.php');
    include SprayFireDirectory::getFrameworkPathSubDirectory('core', 'CoreObject.php');
    include SprayFireDirectory::getFrameworkPathSubDirectory('core', 'ClassLoader.php');

    $ClassLoader = new $ClassLoaderName();
    $ClassLoader->setAutoloader();

    $primaryConfigPath = SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'configuration.json');
    $PrimaryConfigFile = new \SplFileInfo($primaryConfigPath);

    try {
        $PrimaryConfig = new libs\sprayfire\config\JsonConfig($PrimaryConfigFile);
    } catch (\InvalidArgumentException $InvalArgException) {
        // this is a temporary measure until a completed system is in place.
        var_dump($InvalArgException);
        exit;
    }

    echo 'The framework version ' . $PrimaryConfig->framework->version;

    $routesConfigPath = SprayFireDirectory::getAppPathSubDirectory('config', 'json', 'routes.json');
    $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
    try {
        $RoutesConfig = new libs\sprayfire\config\JsonConfig($RoutesConfigFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        var_dump($InvalArgExc);
        exit;
    }

    $Router = new \libs\sprayfire\request\SprayFireRouter($RoutesConfig);

    $Uri = new \libs\sprayfire\request\BaseUri($_SERVER['REQUEST_URI']);

    var_dump($Router->getRoutedUri($Uri));