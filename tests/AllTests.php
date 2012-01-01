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
include 'RestrictedMapTest.php';
include 'BaseUriTest.php';
include 'DirectoryTest.php';
include 'SprayFireRouterTest.php';
include 'DispatchUriTest.php';
include 'FileLoggerTest.php';
include 'DevelopmentLoggerTest.php';
include 'ClassLoaderTest.php';
include 'PathGeneratorBootstrapTest.php';
include 'ConfigBootstrapTest.php';

/**
 * @codeCoverageIgnore
 */
class AllTests extends PHPUnit_Framework_TestSuite {

    protected $ClassLoader;

    /**
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite() {
        $Suite = new AllTests();
        $Suite->addTestSuite('JsonConfigTest');
        $Suite->addTestSuite('MutableStorageTest');
        $Suite->addTestSuite('RestrictedMapTest');
        $Suite->addTestSuite('SprayFireRouterTest');
        $Suite->addTestSuite('BaseUriTest');
        $Suite->addTestSuite('DirectoryTest');
        $Suite->addTestSuite('DispatchUriTest');
        $Suite->addTestSuite('FileLoggerTest');
        $Suite->addTestSuite('DevelopmentLoggerTest');
        $Suite->addTestSuite('ClassLoaderTest');
        $Suite->addTestSuite('PathGeneratorBootstrapTest');
        $Suite->addTestSuite('ConfigBootstrapTest');

        return $Suite;
    }

    protected function setUp() {

    }

    protected function tearDown() {

    }

}

// End AllTests
