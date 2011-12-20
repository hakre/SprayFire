<?php

    /**
     * @var string
     */
    defined('DS') or define('DS', DIRECTORY_SEPARATOR);

    /**
     * @var string
     */
    defined('ROOT_PATH') or define('ROOT_PATH', dirname(dirname(__FILE__)));

    include ROOT_PATH . DS . 'libs' . DS . 'sprayfire' . DS . 'core' . DS . 'FrameworkPaths.php';
    include ROOT_PATH . DS . 'libs' . DS . 'sprayfire' . DS . 'core' . DS . 'SprayFireDirectory.php';

    \libs\sprayfire\core\SprayFireDirectory::setRootInstallationPath(ROOT_PATH);

    include \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory('core', 'Object.php');
    include \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory('core', 'CoreObject.php');
    include \libs\sprayfire\core\SprayFireDirectory::getFrameworkPathSubDirectory('core', 'ClassLoader.php');

    $ClassLoader = new \libs\sprayfire\core\ClassLoader();
    $ClassLoader->setAutoLoader();