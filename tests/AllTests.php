<?php

/**
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.2.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

include 'JsonConfigTest.php';
include 'MutableStorageTest.php';
include 'SprayFireObjectStoreTest.php';
include 'BaseUriTest.php';
include 'SprayFireDirectoryTest.php';
include 'SprayFireRouterTest.php';
include 'SprayFireUriTest.php';
include 'SprayFireHttpDataTest.php';

/**
 *
 */
class AllTests {

    /**
     *
     * @return PHPUnit_Framework_TestSuite
     * @codeCoverageIgnore
     */
    public static function suite() {
        $Suite = new PHPUnit_Framework_TestSuite();
        //$Suite->addTestSuite('FrameworkBootstrapTest');
        $Suite->addTestSuite('JsonConfigTest');
        $Suite->addTestSuite('MutableStorageTest');
        $Suite->addTestSuite('SprayFireObjectStoreTest');
        $Suite->addTestSuite('SprayFireRouterTest');
        $Suite->addTestSuite('BaseUriTest');
        $Suite->addTestSuite('SprayFireDirectoryTest');
        $Suite->addTestSuite('SprayFireUriTest');
        $Suite->addTestSuite('SprayFireHttpDataTest');

        return $Suite;
    }

}

// End AllTests
