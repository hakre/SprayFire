<?php

/**
 * @file
 * @brief The framework's implementation of the libs.sprayfire.core.Object interface.
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
 * @copyright Copyright (c) 2011, Charles Sprayberry
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
     * @brief SprayFire's implementation of the libs.sprayfire.core.Object interface,
     * allowing easy access to some of the functionality required by this interface
     * simply by extending CoreObject.
     *
     * @details
     * Virtually all of the framework objects used by your application will extend
     * this class.  So, if you use the framework as its intended to be used this
     * functionality should be provided to you "out-of-the-box".  When you start
     * creating your own objects be sure they somehow trace back to this object
     * or an implementation of libs.sprayfire.core.Object or unexpected consequences
     * may occur.
     */
    abstract class CoreObject implements \libs\sprayfire\core\Object {

        /**
         * @return A unique identifying string based on the internal memory pointer
         * @see http://us3.php.net/manual/en/function.spl-object-hash.php
         */
        public final function hashCode() {
            $hashCode = spl_object_hash($this);
            return $hashCode;
        }

        /**
         * @brief Default implementation, compares the libs.sprayfire.core.CoreObject::hashCode()
         * return value to the passed \a $CompareObject.
         *
         * @details
         * If your objects need to implement a Comparator be sure to override the
         * implementation of this class.
         *
         * @param $CompareObject A libs.sprayfire.core.Object to compare to this one for equality
         * @return True if the calling object and \a $CompareObject are equal, false if not
         */
        public function equals(\libs\sprayfire\core\Object $CompareObject) {
            $thisHash = $this->hashCode();
            $compareHash = $CompareObject->hashCode();
            $areHashesEqual = $thisHash === $compareHash;
            return $areHashesEqual;
        }

        /**
         * @return A string representation of the calling object
         */
        public function __toString() {
            return \get_class($this);
        }

    }

    // End CoreObject
}

// End libs.sprayfire.core