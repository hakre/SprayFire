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

    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // The below variables can be changed and manipulated to adjust the implementation
    // details of the framework's initialization process.
    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    /**
     * @var $primaryConfigPath An array representing a sub directory list to the
     *      primary configuration file.
     */
    $primaryConfigPath = array('Config', 'json', 'configuration.json');

    /**
     * @var $rootDir The primary installation path for the app
     */
    $rootDir = \dirname(__DIR__);

    include $rootDir . '/libs/SprayFire/Core/Directory.php';

    \SprayFire\Core\Directory::setInstallPath($rootDir);
    \SprayFire\Core\Directory::setLibsPath($rootDir . '/libs');
    \SprayFire\Core\Directory::setAppPath($rootDir . '/app');
    \SprayFire\Core\Directory::setLogsPath($rootDir . '/logs');

    include \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Core', 'Object.php');
    include \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Core', 'CoreObject.php');
    include \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Core', 'ClassLoader.php');

    $ClassLoader = new \SprayFire\Core\ClassLoader();
    $ClassLoader->registerNamespaceDirectory('SprayFire', \SprayFire\Core\Directory::getLibsPath());
    \spl_autoload_register(array($ClassLoader, 'loadClass'));

    $primaryConfigPath = \SprayFire\Core\Directory::getAppPath('Config', 'json', 'configuration.json');
    $PrimaryConfigFile = new \SplFileInfo($primaryConfigPath);
    try {
        $PrimaryConfig = new \SprayFire\Config\JsonConfig($PrimaryConfigFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        // this is a temporary measure until a completed system is in place.
        var_dump($InvalArgException);
        exit;
    }

    $routesConfigPath = \SprayFire\Core\Directory::getAppPath('Config', 'json', 'routes.json');
    $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
    try {
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        // this is a temporary measure until a completed system is in place.
        var_dump($InvalArgExc);
        exit;
    }

    $errorLogPath = \SprayFire\Core\Directory::getLogsPath('errors.txt');
    $ErrorLogFile = new \SplFileInfo($errorLogPath);
    try {
        $ErrorLog = new \SprayFire\Logger\FileLogger($ErrorLogFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        var_dump($InvalArgExc);
        exit;
    }

    $Router = new \SprayFire\Request\SprayFireRouter($RoutesConfig, $ErrorLog);

    $Uri = new \SprayFire\Request\BaseUri($_SERVER['REQUEST_URI']);

    var_dump($Router->getRoutedUri($Uri));