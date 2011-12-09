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

    \libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath(ROOT_PATH);

    include \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory('interfaces') . DS . 'Object.php';
    include \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory('core') . DS . 'CoreObject.php';
    include \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory('core') . DS . 'ClassLoader.php';

    $ClassLoader = new \libs\sprayfire\core\ClassLoader();
    $ClassLoader->setAutoloader();

    $CoreConfiguration = new \libs\sprayfire\core\CoreConfiguration();
    $FrameworkBootstrapper = new \libs\sprayfire\core\FrameworkBootstrap($CoreConfiguration);

    $FrameworkBootstrapper->runBootstrap();

    var_dump($CoreConfiguration->read('from_app'));

    //phpinfo();

