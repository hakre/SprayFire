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
    * @var string
    */
    defined('DS') or define('DS', DIRECTORY_SEPARATOR);

    /**
     * @var string
     */
    defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));

    include ROOT_PATH . DS . 'libs' . DS . 'sprayfire' . DS . 'core' . DS . 'SprayFireDirectory.php';

    SprayFireDirectory::setRootInstallationPath(ROOT_PATH);

    include SprayFireDirectory::getFrameworkPathSubDirectory('core', 'Object.php');
    include SprayFireDirectory::getFrameworkPathSubDirectory('core', 'CoreObject.php');
    include SprayFireDirectory::getFrameworkPathSubDirectory('core', 'ClassLoader.php');

    $ClassLoader = new ClassLoader();
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