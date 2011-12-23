<?php

/**
 * @file
 * @brief The framework's implementation of the libs.sprayfire.request.HttpData
 * interface.
 *
 * @details
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.3.
 *
 * SprayFire makes use of namespaces, a custom-built ORM layer, a completely
 * object oriented approach and minimal invasiveness so you can make the framework
 * do what YOU want to do.  Some things we take seriously over here at SprayFire
 * includes clean, readable source, completely unit tested implementations and
 * not polluting the global scope.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 */
/**
 * @namespace libs.sprayfire.request
 * @brief Contains all classes and interfaces needed to parse the requested URI
 * and manage the HTTP data, both headers and normal GET/POST data, that get passed
 * in each request.
 */

/**
 *
 */
class SprayFireHttpDataTest extends PHPUnit_Framework_TestCase {

    public function testBasicReferenceAssociation() {
        $testArray = array('key' => 'manipulate this');
        $HttpData = new libs\sprayfire\request\SprayFireHttpData($testArray);
        $this->assertSame('manipulate this', $HttpData->key);
        $testArray['key'] = 'something else';
        $this->assertSame('something else', $HttpData->key);
        $HttpData->key = 'yet again, something different';
        $this->assertSame('yet again, something different', $testArray['key']);
    }

    public function testBasicIteratingFunctionality() {
        $testArray = array(1, 2, 3, 4, 5);
        $HttpData = new libs\sprayfire\request\SprayFireHttpData($testArray);

        $i = 1;
        foreach ($HttpData as $value) {
            $this->assertSame($i, $value);
            $i++;
        }
    }

    public function testAssociativeKeysThroughIteration() {
        $testArray = array('one' => 'value', 'two' => 'another', 'three' => 'something');
        $HttpData = new libs\sprayfire\request\SprayFireHttpData($testArray);

        $expectedKeys = array('one', 'two', 'three');
        $expectedValues = array('value', 'another', 'something');
        $i = 0;
        foreach ($HttpData as $key => $value) {
            $this->assertSame($expectedKeys[$i], $key);
            $this->assertSame($expectedValues[$i], $value);
            $i++;
        }
    }

    public function testRemovingValuesThroughIteration() {
        $testArray = array('one' => 'value', 'two' => 'another', 'three' => 'delete', 'four' => 'something');
        $HttpData = new libs\sprayfire\request\SprayFireHttpData($testArray);

        $expectedKeys = array('one', 'two', 'four');
        $expectedValues = array('value', 'another', 'something');
        $i = 0;
        foreach ($HttpData as $key => $value) {
            if ($key === 'three') {
                unset($HttpData->$key);
                continue;
            }
            $this->assertSame($expectedKeys[$i], $key);
            $this->assertSame($expectedValues[$i], $value);
            $i++;
        }

        $this->assertFalse(isset($HttpData->three));
        $this->assertFalse(isset($testArray['three']));
        $this->assertCount(3, $HttpData);

    }

}

// End SprayFireHttpDataTest