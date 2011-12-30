<?php

    defined('SPRAYFIRE_ROOT') or define('SPRAYFIRE_ROOT', \dirname(__DIR__));

    include \SPRAYFIRE_ROOT .'/libs/SprayFire/Core/Directory.php';
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/ClassLoader.php';

    \SprayFire\Core\Directory::setLibsPath(\SPRAYFIRE_ROOT . '/libs');
    $ClassLoader = new \SprayFire\Core\ClassLoader();
    $ClassLoader->registerNamespaceDirectory('SprayFire', \SprayFire\Core\Directory::getLibsPath());
    \spl_autoload_register(array($ClassLoader, 'loadClass'));
    \SprayFire\Core\Directory::setLibsPath(null);