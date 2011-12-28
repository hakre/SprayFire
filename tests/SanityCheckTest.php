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
class SanityCheckTest extends PHPUnit_Framework_TestCase {

    public function testSanityCheckNoLogsPathSetOnlyThingWrong() {
        \SprayFire\Core\Directory::setLogsPath(null);
        \SprayFire\Core\Directory::setLibsPath(\dirname(__DIR__) . '/tests/mockframework/libs');
        \touch(\SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration.json'));
        $SanityCheck = new \SprayFire\Core\SanityCheck();
        $errors = $SanityCheck->verifySanity();
        $expected = array('The logs path was not set properly, please ensure you invoke \\SprayFire\\Core\\Directory::setLogsPath()');
        $this->assertSame($expected, $errors);
        if (\file_exists(\SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration.json'))) {
            \unlink(\SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration.json'));
        }
    }

    public function testSanityCheckLogsPathNotWritableOnlyThingWrong() {
        \SprayFire\Core\Directory::setLogsPath(\dirname(__DIR__) . '/tests/mockframework/logs');
        \SprayFire\Core\Directory::setLibsPath(\dirname(__DIR__) . '/tests/mockframework/libs');
        \touch(\SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration.json'));
        \chmod(\SprayFire\Core\Directory::getLogsPath(), 0444);

        $SanityCheck = new \SprayFire\Core\SanityCheck();
        $errors = $SanityCheck->verifySanity();
        $expected = array('The logs path set, /Library/WebServer/Documents/framework/tests/mockframework/logs, is not writable.  Please change the permissions on this directory to allow writing.');
        $this->assertSame($expected, $errors);
        \chmod(\SprayFire\Core\Directory::getLogsPath(), 0777);
        if (\file_exists(\SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration.json'))) {
            \unlink(\SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration.json'));
        }
    }

    public function testSanityCheckConfigurationPathNotExistOnlyThingWrong() {
        \SprayFire\Core\Directory::setLogsPath(\dirname(__DIR__) . '/tests/mockframework/logs');
        \SprayFire\Core\Directory::setLibsPath(\dirname(__DIR__) . '/tests/mockframework/libs');
        \chmod(\SprayFire\Core\Directory::getLogsPath(), 0777);
        $SanityCheck = new \SprayFire\Core\SanityCheck();
        $errors = $SanityCheck->verifySanity();
        $expected = array('The primary configuration path, ' . \SprayFire\Core\Directory::getLibsPath('SprayFire', 'Config', 'json', 'configuration.json') . ', does not exist.  Please create the appropriate configuration file.');
        $this->assertSame($expected, $errors);
    }

    public function tearDown() {
        \SprayFire\Core\Directory::setLogsPath(null);
        \SprayFire\Core\Directory::setLibsPath(null);
    }

}