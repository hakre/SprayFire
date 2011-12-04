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

include 'CoreConfigurationTest.php';
include 'RequestParserTest.php';
include 'UniqueListTest.php';

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
        $Suite->addTestSuite('CoreConfigurationTest');
        $Suite->addTestSuite('RequestParserTest');
        $Suite->addTestSuite('UniqueListTest');
        return $Suite;
    }

}

// End AllTests
