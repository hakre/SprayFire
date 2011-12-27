<?php

    include \dirname(__DIR__) .'/libs/sprayfire/core/Directory.php';

    \SprayFire\Core\Directory::setInstallPath(\dirname(__DIR__));
    \SprayFire\Core\Directory::setLibsPath(\dirname(__DIR__) . '/libs');

    include \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Core', 'Object.php');
    include \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Core', 'CoreObject.php');
    include \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Core', 'ClassLoader.php');

    $ClassLoader = new \SprayFire\Core\ClassLoader();
    $ClassLoader->registerNamespaceDirectory('SprayFire', \SprayFire\Core\Directory::getLibsPath());
    \spl_autoload(array($ClassLoader, 'loadClass'));