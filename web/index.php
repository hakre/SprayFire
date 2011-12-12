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
    defined('ROOT_PATH') or define('ROOT_PATH', dirname(dirname(__FILE__)));

    include ROOT_PATH . DS . 'libs' . DS . 'sprayfire' . DS . 'interfaces' . DS . 'FrameworkPaths.php';
    include ROOT_PATH . DS . 'libs' . DS . 'sprayfire' . DS . 'core' . DS . 'SprayFireDirectory.php';

    SprayFireDirectory::setRootInstallationPath(ROOT_PATH);

    include SprayFireDirectory::getFrameworkPathSubDirectory('interfaces') . DS . 'Object.php';
    include SprayFireDirectory::getFrameworkPathSubDirectory('core') . DS . 'CoreObject.php';
    include SprayFireDirectory::getFrameworkPathSubDirectory('core') . DS . 'ClassLoader.php';

    $ClassLoader = new ClassLoader();
    $ClassLoader->setAutoloader();

    $frameworkConfigFile = SprayFireDirectory::getFrameworkPathSubDirectory('config', 'xml') . DS . 'framework-config.xml';
    $FrameworkConfig = new libs\sprayfire\config\FrameworkConfig();

    try {
        if (!file_exists($frameworkConfigFile)) {
            $FrameworkConfig->importConfig($filePath);
        }
    } catch (\InvalidArgumentException $InvalArgExc) {
        error_log('There was an error importing the framework\'s configuration, please check the config file path.');
    }