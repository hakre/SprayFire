<?php

/**
 * @file
 * @brief
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
 *
 */
class DispatchUriTest extends PHPUnit_Framework_TestCase {

    public function testOriginalUriWithNoSettings() {
        $originalUri = '/';
        $routedUri = '/pages/index/';
        $RoutedUri = new \SprayFire\Request\DispatchUri($routedUri);
        $RoutedUri->setOriginalUri($originalUri);

        $this->assertSame($originalUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUri, $RoutedUri->getRoutedUri());
        $this->assertSame('pages', $RoutedUri->getController());
        $this->assertSame('index', $RoutedUri->getAction());
        $this->assertSame(array(), $RoutedUri->getParameters());
    }

    public function testOriginalUriWithOnlyController() {
        $originalUri = '/sprayfire/dogs/';
        $routedUri = '/dogs/train/stay';
        $RoutedUri = new \SprayFire\Request\DispatchUri($routedUri);
        $RoutedUri->setOriginalUri($originalUri);

        $this->assertSame($originalUri, $RoutedUri->getOriginalUri());
        $this->assertSame($routedUri, $RoutedUri->getRoutedUri());
        $this->assertSame('dogs', $RoutedUri->getController());
        $this->assertSame('train', $RoutedUri->getAction());
        $this->assertSame(array('stay'), $RoutedUri->getParameters());
    }

}

// End SprayFireUriTest