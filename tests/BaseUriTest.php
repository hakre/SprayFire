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

/**
 *
 */
class BaseUriTest extends PHPUnit_Framework_TestCase {

    public function testBaseUriWithNoPath() {
        $originalUri = '/sprayfire/';
        $Uri = new \libs\sprayfire\request\BaseUri($originalUri);

        $this->assertSame($originalUri, $Uri->getRawUri());
        $this->assertSame(\libs\sprayfire\request\Uri::DEFAULT_CONTROLLER, $Uri->getController());
        $this->assertSame(\libs\sprayfire\request\Uri::DEFAULT_ACTION, $Uri->getAction());
        $this->assertSame(array(), $Uri->getParameters());

        $routedUri = '/pages/index';
        $Uri->setRoutedUri($routedUri);
        $this->assertSame($originalUri, $Uri->getRawUri());
        $this->assertSame('pages', $Uri->getController());
        $this->assertSame('index', $Uri->getAction());
        $this->assertSame(array(), $Uri->getParameters());
    }

    public function testBaseUriWithNoFrameworkOnlyController() {
        $originalUri = '/pages/';
        $Uri = new libs\sprayfire\request\BaseUri($originalUri);

        $this->assertSame($originalUri, $Uri->getRawUri());
        $this->assertSame('pages', $Uri->getController());
        $this->assertSame(\libs\sprayfire\request\Uri::DEFAULT_ACTION, $Uri->getAction());
        $this->assertSame(array(), $Uri->getParameters());
    }

    public function testBaseUriOriginalControllerActionParam() {

        $originalUri = '/sprayfire/controller/action/param1';
        $Uri = new \libs\sprayfire\request\BaseUri($originalUri);

        $this->assertSame($originalUri, $Uri->getRawUri());
        $this->assertSame('controller', $Uri->getController());
        $this->assertSame('action', $Uri->getAction());
        $this->assertSame(array('param1'), $Uri->getParameters());

    }

    public function testBaseUriWithMarkedParametersNoAction() {

        $originalUri = '/sprayfire/pages/:param1/:param2';
        $Uri = new \libs\sprayfire\request\BaseUri($originalUri);

        $this->assertSame($originalUri, $Uri->getRawUri());
        $this->assertSame('pages', $Uri->getController());
        $this->assertSame(\libs\sprayfire\request\Uri::DEFAULT_ACTION, $Uri->getAction());
        $this->assertSame(array('param1', 'param2'), $Uri->getParameters());

    }

    public function testBaseUriWithMarkedParametersOnly() {

        $originalUri = '/sprayfire/:tech/:sprayfire-is-the-best';
        $Uri = new \libs\sprayfire\request\BaseUri($originalUri);

        $this->assertSame($originalUri, $Uri->getRawUri());
        $this->assertSame(\libs\sprayfire\request\Uri::DEFAULT_CONTROLLER, $Uri->getController());
        $this->assertSame(\libs\sprayfire\request\Uri::DEFAULT_ACTION, $Uri->getAction());
        $params = $Uri->getParameters();
        $this->assertSame(2, \count($params));
        $actualParamOne = $params[0];
        $this->assertSame('tech', $actualParamOne);
        $actualParamTwo = $params[1];
        $this->assertSame('sprayfire-is-the-best', $actualParamTwo);

    }

}

// End BaseUriTest
