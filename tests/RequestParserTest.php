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

// For now this is a crude test, once the TestSuite is completed we will incorporate
// this code into the TestSuite.

$overloadablePath = '../libs/sprayfire/interfaces/Overloadable.php';
$coreObjectPath = '../libs/sprayfire/core/CoreObject.php';
$requestParserPath = '../libs/sprayfire/core/RequestParser.php';

include $overloadablePath;
include $coreObjectPath;
include $requestParserPath;

/**
 * The unit tests to ensure the SF_RequestParser object is returning the proper
 * values.
 */
class RequestParserTest extends PHPUnit_Framework_TestCase {

    /**
     * Will generate the necessary SF_RequestParser object, passing a known URI
     * string, and then assert that the controller, action and parameters returned
     * by the SF_RequestParser object is of the correct type.
     *
     * @todo This function needs to be refactored as it is currently far too long,
     * taking on too many things and has duplicate code.
     */
    public function testUriWithFramework() {
        $testUri = '/sprayfire/testwith/frameworkfolder/1/2/3';
        $RequestParser = new RequestParser($testUri);

        $expectedController = 'testwith';
        $expectedAction = 'frameworkfolder';
        $expectedParameters = array(1, 2, 3);

        $actualController = $RequestParser->getRequestedController();
        $actualAction = $RequestParser->getRequestedAction();
        $actualParameters = $RequestParser->getSentParameters();

        $this->assertEquals($expectedController, $actualController);
        $this->assertEquals($expectedAction, $actualAction);
        $this->assertEquals($expectedParameters, $actualParameters);

    }

    public function testUriWithOutFramework() {
        $testUri = '/second/test/a/b/c';
        $RequestParser = new RequestParser($testUri);

        $expectedController = 'second';
        $expectedAction = 'test';
        $expectedParameters = array('a', 'b', 'c');

        $actualController = $RequestParser->getRequestedController();
        $actualAction = $RequestParser->getRequestedAction();
        $actualParameters = $RequestParser->getSentParameters();

        $this->assertEquals($expectedController, $actualController);
        $this->assertEquals($expectedAction, $actualAction);
        $this->assertEquals($expectedParameters, $actualParameters);
    }

}

// End SF_RequestParserTest
