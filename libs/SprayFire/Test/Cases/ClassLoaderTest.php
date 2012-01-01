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

namespace SprayFire\Test\Cases;

/**
 * @brief
 */
class ClassLoaderTest extends \PHPUnit_Framework_TestCase {

    public function testNamespaceDirectoryLoad() {
        $ClassLoader = new \SprayFire\Core\ClassLoader();
        $ClassLoader->registerNamespaceDirectory('TestApp', \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app');
        $this->assertTrue($ClassLoader->load('\\TestApp\\Controller\\TestController'));
        $Controller = new \TestApp\Controller\TestController();
        $this->assertTrue(\is_object($Controller));
    }

    public function testNoNamespaceLoad() {
        $ClassLoader = new \SprayFire\Core\ClassLoader();
        $this->assertFalse($ClassLoader->load('NoNamespace'));
    }

    public function testNoClassLoad() {
        $ClassLoader = new \SprayFire\Core\ClassLoader();
        $ClassLoader->registerNamespaceDirectory('SprayFire', \SPRAYFIRE_ROOT . '/libs');
        $this->assertFalse($ClassLoader->load('\\SprayFire\\Core\\NoExist'));
    }

    public function testGettingRegisteredNamespaces() {
        $ClassLoader = new \SprayFire\Core\ClassLoader();
        $ClassLoader->registerNamespaceDirectory('SprayFire', \SPRAYFIRE_ROOT);
        $ClassLoader->registerNamespaceDirectory('SomethingElse', \SPRAYFIRE_ROOT . '/something_else');
        $ClassLoader->registerNamespaceDirectory('Again', \SPRAYFIRE_ROOT . '/again');

        $expected = array();
        $expected['SprayFire'] = \SPRAYFIRE_ROOT;
        $expected['SomethingElse'] = \SPRAYFIRE_ROOT . '/something_else';
        $expected['Again'] = \SPRAYFIRE_ROOT . '/again';
        $actual = $ClassLoader->getRegisteredNamespaces();
        $this->assertSame($expected, $actual);

    }

}