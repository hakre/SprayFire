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
        $tests = array(
            'BaseUriTest',
            'ClassLoaderTest',
            'ConfigBootstrapTest',
            'DevelopmentLoggerTest',
            'DirectoryTest',
            'DispatchUriTest',
            'FileLoggerTest',
            'JsonConfigTest',
            'MutableStorageTest',
            'PathGeneratorBootstrapTest',
            'RestrictedMapTest',
            'SprayFireRouterTest'
        );

        \shuffle($tests);
        $Suite = new AllTests();

        foreach ($tests as $test) {
            include './cases/' . $test . '.php';
            $Suite->addTestSuite($test);
        }

        return $Suite;

    }

}

// End AllTests
