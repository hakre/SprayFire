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

/**
 * @brief
 */
class SprayFireTestCase extends PHPUnit_Framework_TestCase {

    protected $ClassLoader;

    public function setUp() {
        \SprayFire\Core\Directory::setInstallPath(\SPRAYFIRE_ROOT);
        \SprayFire\Core\Directory::setLibsPath(\SPRAYFIRE_ROOT . '/libs');
        $this->ClassLoader = new \SprayFire\Core\ClassLoader();
        $this->ClassLoader->registerNamespaceDirectory('SprayFire', \SprayFire\Core\Directory::getLibsPath());
        $this->assertTrue(\spl_autoload_register(array($this->ClassLoader, 'loadClass')));

        \SprayFire\Core\Directory::setLibsPath(null);
        $this->assertNull(\SprayFire\Core\Directory::getLibsPath());
    }

    public function tearDown() {
        \SprayFire\Core\Directory::setAppPath(null);
        \SprayFire\Core\Directory::setInstallPath(null);
        \SprayFire\Core\Directory::setLibsPath(null);
        \SprayFire\Core\Directory::setLogsPath(null);
        \SprayFire\Core\Directory::setWebPath(null);

        $this->assertTrue(\spl_autoload_unregister(array($this->ClassLoader, 'loadClass')));

        $this->assertNull(\SprayFire\Core\Directory::getAppPath());
        $this->assertNull(\SprayFire\Core\Directory::getInstallPath());
        $this->assertNull(\SprayFire\Core\Directory::getLibsPath());
        $this->assertNull(\SprayFire\Core\Directory::getLogsPath());
        $this->assertNull(\SprayFire\Core\Directory::getWebPath());
    }

}