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

$requestParserPath = '../libs/sprayfire/core/SF_RequestParser.php';
include $requestParserPath;

/**
 * The unit tests to ensure the SF_RequestParser object is returning the proper
 * values.
 */
class SF_RequestParserTest extends PHPUnit_Framework_TestCase {

    /**
     * Will generate the necessary SF_RequestParser object, passing a known URI
     * string, and then assert that the controller, action and parameters returned
     * by the SF_RequestParser object is of the correct type.
     *
     * @todo This function needs to be refactored as it is currently far too long,
     * taking on too many things and has duplicate code.
     */
    public function testRequestParser() {

        echo 'Testing the SF_RequestParser functions to gather controller, action, parameters:';
        echo '<br />';
        echo 'Using URL: /sprayfire/testwith/frameworkfolder/1/2/3';
        echo '<br />';

        $testUri = '/sprayfire/testwith/frameworkfolder/1/2/3';
        $RequestParser = new SF_RequestParser($testUri);

        $expectedController = 'testwith';
        $expectedAction = 'frameworkfolder';
        $expectedParameters = array(1, 2, 3);

        $actualController = $RequestParser->getRequestedController();
        $actualAction = $RequestParser->getRequestedAction();
        $actualParameters = $RequestParser->getSentParameters();

        $failedAssertions = array();
        try {
            $this->assertEquals($expectedController, $actualController);
        } catch (PHPUnit_Framework_ExpectationFailedException $ExpectationException) {
            $failedAssertions['controller_assertion'] = array();
            $failedAssertions['controller_assertion']['details'] = $ExpectationException->getMessage();
            $failedAssertions['controller_assertion']['trace'] = $ExpectationException->getTrace();
        }

        try {
            $this->assertEquals($expectedAction, $actualAction);
        } catch (PHPUnit_Framework_ExpectationFailedException $ExpectationException) {
            $failedAssertions['action_assertion'] = array();
            $failedAssertions['action_assertion']['details'] = $ExpectationException->getMessage();
            $failedAssertions['action_assertion']['trace'] = $ExpectationException->getTrace();
        }

        try {
            $this->assertEquals($expectedParameters, $actualParameters);
        } catch (PHPUnit_Framework_ExpectationFailedException $ExpectationException) {
            $failedAssertions['parameter_assertion'] = array();
            $failedAssertions['parameter_assertion']['details'] = $ExpectationException->getMessage();
            $failedAssertions['parameter_assertion']['trace'] = $ExpectationException->getTrace();
        }

        if (empty($failedAssertions)) {
            echo 'The tests passed!';
        } else {
            echo 'The tests FAILED!';
            echo '<pre>';
            echo var_dump($failedAssertions);
            echo '</pre>';
        }

    }

}

// End SF_RequestParserTest
