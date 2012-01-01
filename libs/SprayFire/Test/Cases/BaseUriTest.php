<?php

/**
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
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace SprayFire\Test\Cases;

/**
 *
 */
class BaseUriTest extends \PHPUnit_Framework_TestCase {

    private $baseDir;

    public function setUp() {
        $this->baseDir = '/' . \basename(\SPRAYFIRE_ROOT) . '/';


    }

    public function testBaseUriWithNoPath() {
        $Uri = new \SprayFire\Request\BaseUri($this->baseDir, $this->baseDir);

        $this->assertSame($this->baseDir, $Uri->getOriginalUri());
        $this->assertSame(\SprayFire\Request\Uri::DEFAULT_CONTROLLER, $Uri->getController());
        $this->assertSame(\SprayFire\Request\Uri::DEFAULT_ACTION, $Uri->getAction());
        $this->assertSame(array(), $Uri->getParameters());
    }

    public function testBaseUriWithNoFrameworkOnlyController() {
        $originalUri = '/pages/';
        $Uri = new \SprayFire\Request\BaseUri($originalUri, $this->baseDir);

        $this->assertSame($originalUri, $Uri->getOriginalUri());
        $this->assertSame('pages', $Uri->getController());
        $this->assertSame(\SprayFire\Request\Uri::DEFAULT_ACTION, $Uri->getAction());
        $this->assertSame(array(), $Uri->getParameters());
    }

    public function testBaseUriOriginalControllerActionParam() {

        $originalUri = $this->baseDir . 'controller/action/param1';
        $Uri = new \SprayFire\Request\BaseUri($originalUri, $this->baseDir);

        $this->assertSame($originalUri, $Uri->getOriginalUri());
        $this->assertSame('controller', $Uri->getController());
        $this->assertSame('action', $Uri->getAction());
        $this->assertSame(array('param1'), $Uri->getParameters());

    }

    public function testBaseUriWithControllerAndActionNoParams() {
        $originalUri = '/pages/view/';
        $Uri = new \SprayFire\Request\BaseUri($originalUri, $this->baseDir);

        $this->assertSame($originalUri, $Uri->getOriginalUri());
        $this->assertSame('pages', $Uri->getController());
        $this->assertSame('view', $Uri->getAction());
        $this->assertSame(array(), $Uri->getParameters());
    }

    public function testBaseUriWithMarkedParametersNoAction() {

        $originalUri = $this->baseDir . 'pages/:param1/:param2';
        $Uri = new \SprayFire\Request\BaseUri($originalUri, $this->baseDir);

        $this->assertSame($originalUri, $Uri->getOriginalUri());
        $this->assertSame('pages', $Uri->getController());
        $this->assertSame(\SprayFire\Request\Uri::DEFAULT_ACTION, $Uri->getAction());
        $this->assertSame(array('param1', 'param2'), $Uri->getParameters());

    }

    public function testBaseUriWithMarkedParametersOnly() {

        $originalUri = $this->baseDir . ':tech/:sprayfire-is-the-best';
        $Uri = new \SprayFire\Request\BaseUri($originalUri, $this->baseDir);

        $this->assertSame($originalUri, $Uri->getOriginalUri());
        $this->assertSame(\SprayFire\Request\Uri::DEFAULT_CONTROLLER, $Uri->getController());
        $this->assertSame(\SprayFire\Request\Uri::DEFAULT_ACTION, $Uri->getAction());
        $params = $Uri->getParameters();
        $this->assertSame(2, \count($params));
        $actualParamOne = $params[0];
        $this->assertSame('tech', $actualParamOne);
        $actualParamTwo = $params[1];
        $this->assertSame('sprayfire-is-the-best', $actualParamTwo);

    }

    public function testUriEqualsWithTwoEqualObjects() {
        $FirstUri = new \SprayFire\Request\BaseUri($this->baseDir . 'dogs/train/:stay', $this->baseDir);
        $SecondUri = new \SprayFire\Request\BaseUri($this->baseDir . 'dogs/train/:stay', $this->baseDir);
        $this->assertTrue($FirstUri->equals($SecondUri));
    }

    public function testUriEqualsWithTwoNotEqualObjects() {
        $FirstUri = new \SprayFire\Request\BaseUri($this->baseDir . 'dogs/train/:stay', $this->baseDir);
        $SecondUri = new \SprayFire\Request\BaseUri($this->baseDir . 'dogs/train/:sit', $this->baseDir);
        $this->assertFalse($FirstUri->equals($SecondUri));
    }

    public function testUriEqualsWithNonUriObject() {
        $FirstUri = new \SprayFire\Request\BaseUri($this->baseDir . 'dogs/train/:stay', $this->baseDir);
        $SecondUri = new \SprayFire\Test\Helpers\TestObject();
        $this->assertFalse($FirstUri->equals($SecondUri));
    }

}

// End BaseUriTest
