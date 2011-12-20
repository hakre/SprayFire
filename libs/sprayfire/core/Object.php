<?php

/**
 * @file
 * @brief An interface representing a basic framework object, used as a means to
 * ensure some very basic, common object functionality.
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
 * @namespace libs.sprayfire.core
 * @brief Holds parts of the framework that are considered essential for SprayFire
 * to operate.
 *
 * @details
 * The classes and interfaces in this namespace are generally things that work
 * with the file system, load classes and otherwise make sure the framework has
 * a common foundation to work with.  Ultimately you can almost think of this as
 * a "utility" namespace, but ultimately goes into the heart of the framework.
 */
namespace libs\sprayfire\core {

    /**
     * @brief An interface for basic framework objects.
     *
     * @details
     * Primarily this interface is used to ensure that there is a way to
     *
     * - represent a class as a string, for debugging purposes if necessary or
     * for pure data objects and easy output
     * - compare 2 objects to see if they are equal to each other based on a means
     * that makes sense for the given object
     * - return a unique identifier for the given object
     */
    interface Object {

        /**
         * @brief Compare two objects to see if they are equal to each other.
         *
         * @param $Object libs.sprayfire.core.Object
         * @return boolean
         */
        public function equals(\libs\sprayfire\core\Object $Object);

        /**
         * @return A unique identifier for the given object
         */
        public function hashCode();

        /**
         * @return A string representation for the object
         */
        public function __toString();

    }

    // End Object

}

// End libs.sprayfire.core.Object