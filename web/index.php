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
     * @var $rootDir The primary installation path for the app
     */
    $rootDir = \dirname(__DIR__);

    // Please do not include any trailing slashes on directories!

    /**
     * @brief WARNING!  Only change this directory if you are sure to move the
     * <code>SprayFire</code> library to that directory.
     *
     * @var $libsPath The path holding SprayFire and third-party libraries
     */
    $libsPath = $rootDir . '/libs';

    /**
     * @var $appPath The path holding app libraries and classes
     */
    $appPath = $rootDir . '/app';

    /**
     * @var $logsPath The path holding log files used by the app or framework
     */
    $logsPath = $rootDir . '/logs';

    /**
     * @var $webPath The path holding web accessible files
     */
    $webPath = $rootDir . '/web';

    // PLEASE DO NOT CHANGE CODE BELOW THIS LINE!

    include $libsPath . '/SprayFire/Core/Directory.php';

    // Be sure to set the following paths in \SprayFire\Core\Directory
    // - installPath
    // - libsPath
    // - appPath
    // - logsPath
    // - webPath
    \SprayFire\Core\Directory::setInstallPath($rootDir);
    \SprayFire\Core\Directory::setLibsPath($libsPath);
    \SprayFire\Core\Directory::setAppPath($appPath);
    \SprayFire\Core\Directory::setLogsPath($logsPath);
    \SprayFire\Core\Directory::setWebPath($webPath);

    include \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Core', 'ClassLoader.php');

    $ClassLoader = new \SprayFire\Core\ClassLoader();
    $ClassLoader->registerNamespaceDirectory('SprayFire', \SprayFire\Core\Directory::getLibsPath());
    \spl_autoload_register(array($ClassLoader, 'loadClass'));

    $SanityCheck = new \SprayFire\Core\SanityCheck();
    $sanityFailures = $SanityCheck->verifySanity();
    

    $errorLogPath = \SprayFire\Core\Directory::getLogsPath('errors.txt');
    $ErrorLogFile = new \SplFileInfo($errorLogPath);
    try {
        $ErrorLog = new \SprayFire\Logger\FileLogger($ErrorLogFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        // This is a fail-safe to ensure that there is an ErrorLog for various
        // objects needing to log error messages
        $ErrorLog = new \SprayFire\Logger\FailSafeLogger();
    }

    $primaryConfigPath = \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration.json');
    $PrimaryConfigFile = new \SplFileInfo($primaryConfigPath);
    try {
        $PrimaryConfig = new \SprayFire\Config\JsonConfig($PrimaryConfigFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        /**
         * @todo This needs to be changed so that the basic values we are looking
         * for are created in an ArrayConfig object
         */
        $configData = array();
        $configData['framework'] = array();
        $configData['framework']['version'] = '0.0.0-e';

        $configData['app'] = array();
        $configData['app']['version'] = '0.0.0-e';
        $configData['app']['development-mode'] = 'off';

        $PrimaryConfig = new \SprayFire\Config\ArrayConfig($configData);
    }

    var_dump($PrimaryConfig);

    $routesConfigPath = \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'routes.json');
    $RoutesConfigFile = new \SplFileInfo($routesConfigPath);
    try {
        $RoutesConfig = new \SprayFire\Config\JsonConfig($RoutesConfigFile);
    } catch (\InvalidArgumentException $InvalArgExc) {
        $data = array();
        $data['defaults'] = array();
        $data['defaults']['controller'] = 'pages';
        $data['defaults']['action'] = 'index';
    }

    $Router = new \SprayFire\Request\SprayFireRouter($RoutesConfig, $ErrorLog);
    $Uri = new \SprayFire\Request\BaseUri($_SERVER['REQUEST_URI']);
    $RoutedUri = $Router->getRoutedUri($Uri);


    var_dump($_GET);