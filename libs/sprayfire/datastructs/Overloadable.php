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
 * @namespace libs.sprayfire.datastructs
 * @brief Holds the API used by the framework to store and transfer sets of data.
 */
namespace libs\sprayfire\datastructs {

    /**
     * An interface that forces the implementation of the magic methods that allow
     * an object to access non-existing or inaccessible properties.
     *
     * Please note, implementing this interface does not necessarily mean that the object
     * is mutable or immutable, simply that the implementing object must do something
     * when these methods are called.
     *
     * For methods that are option if the method should not be allowed that method
     * should throw a libs.sprayfire.exceptions.UnsupportedOperationException.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    interface Overloadable {

        /**
         * @param $key string
         * @return mixed
         */
        public function __get($key);

        /**
         * @param $key string
         * @param $value mixed
         * @return mixed
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        public function __set($key, $value);

        /**
         * @param $key string
         * @return boolean
         */
        public function __isset($key);

        /**
         * @param $key string
         * @return boolean
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        public function __unset($key);

    }

    // End Overloadable


}

