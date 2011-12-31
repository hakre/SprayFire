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

// Change the below variables to adjust the SprayFire.Core.PathGenerator
// implementation created to suite the specific layout or needs of your app

// The path where the app, libs, config and web directories are stored
$installPath = \dirname(__DIR__);

// The path where SprayFire and third-party libs are stored
$libsPath = $installPath . '/libs';

// The path where your app files and classes are stored
$appPath = $installPath . '/app';

// The path where the error and stat logs should be stored, should be writable
$logsPath = $installPath . '/logs';

// The path holding the configuration file needed by SprayFire
$configPath = $installPath .'/config';

// The path holding web accessible files
$webPath = $installPath . '/web';

// PLEASE DO NOT CHANGE CODE BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING!

// SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE SPRAYFIRE

include $libsPath . '/SprayFire/Bootstrap/Bootstrapper.php';
include $libsPath . '/SprayFire/Bootstrap/PathGeneratorBootstrap.php';

$paths = \compact('installPath', 'libsPath', 'appPath', 'logsPath', 'configPath', 'webPath');
$PathGenBootstrap = new \SprayFire\Bootstrap\PathGeneratorBootstrap($paths);
$PathGenBootstrap->runBootstrap();

include $libsPath . '/SprayFire/Bootstrap/ClassLoaderBootstrap.php';




$Directory = $PathGenBootstrap->getPathGenerator();
var_dump($Directory);