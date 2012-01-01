<?php

/**
 * @file
 * @brief
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

if (!interface_exists('\\SprayFire\\Bootstrap\\Bootstrapper')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Bootstrap/Bootstrapper.php';
}

if (!class_exists('\\SprayFire\\Bootstrap\\PathGeneratorBootstrap')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Bootstrap/PathGeneratorBootstrap.php';
}

if (!interface_exists('\\SprayFire\\Core\\PathGenerator')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/PathGenerator.php';
}

if (!interface_exists('\\SprayFire\\Core\\Object')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Object.php';
}

if (!class_exists('\\SprayFire\\Core\\CoreObject')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/CoreObject.php';
}

if (!class_exists('\\SprayFire\\Core\\Directory')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Directory.php';
}

/**
 * @brief
 */
class PathGeneratorBootstrapTest extends PHPUnit_Framework_TestCase {

    public function testPathGeneratorBootstrap() {

        $installPath = '/install';
        $libsPath = $installPath . '/libs';
        $appPath = $installPath . '/app';
        $logsPath = $installPath . '/logs';
        $configPath = $installPath . '/config';
        $webPath = $installPath . '/web';
        $paths = \compact('installPath', 'libsPath', 'appPath', 'logsPath', 'configPath', 'webPath');
        $PathGen = new \SprayFire\Bootstrap\PathGeneratorBootstrap($paths);
        $PathGen->runBootstrap();
        $Directory = $PathGen->getPathGenerator();
        $this->assertSame($installPath, $Directory->getInstallPath());
        $this->assertSame($libsPath, $Directory->getLibsPath());
        $this->assertSame($appPath, $Directory->getAppPath());
        $this->assertSame($configPath, $Directory->getConfigPath());
        $this->assertSame($webPath, $Directory->getWebPath());
        $this->assertSame('/install/web', $Directory->getUrlPath());
    }

}