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
namespace libs\sprayfire\request {

    /**
     * @brief Will allow for an array to be passed, the values of that array to be
     * treated as an object, and for changes to the array or the object to make
     * changes to the other.
     */
    class SprayFireHttpData extends \libs\sprayfire\datastructs\MutableStorage implements \IteratorAggregate, \libs\sprayfire\request\HttpData {

        /**
         * @param $data An array passed by reference
         */
        public function __construct(array &$data) {
            $this->data =& $data;
        }

        /**
         * @return \ArrayIterator
         */
        public function getIterator() {
            return new \ArrayIterator($this->data);
        }

    }

    // End SprayFireHttpData
}

// End libs.sprayfire