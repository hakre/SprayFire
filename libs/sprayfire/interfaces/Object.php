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

namespace libs\sprayfire\interfaces;

/**
 * The base interface all objects in the framework must implement; simply provides
 * a way to compare 2 objects, distinguish unique objects and return a textual
 * representation of the class.
 */
interface Object {

    /**
     * Should check if the 2 objects are equal to one another, feel free to override
     * this method, simply ensure that it returns true if object passed is equal
     * to the calling object and false if it is not.
     *
     * @param Object $Object
     * @return boolean
     */
    public function equals(Object $Object);

    /**
     * Should return a unique identifier for the calling object.
     *
     * @return string
     */
    public function hashCode();

    /**
     * @return string
     */
    public function __toString();

}

// End Object
