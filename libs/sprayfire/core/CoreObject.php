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

/**
 * The base object for all application classes, allows for the overloading of
 * properties.
 */
abstract class CoreObject {

    /**
     * Returns a unique identifier for the calling object.
     *
     * @return string
     */
    public final function hashCode() {
        $hashCode = spl_object_hash($this);
        return $hashCode;
    }

    /**
     * The default implemenation for the equals function, will compare the hash
     * code of the object passed and the calling object.
     *
     * If your objects need to implement a Comparator be sure to override the
     * implementation of this class.
     *
     * @param CoreObject $CompareObject
     * @return boolean
     */
    public function equals(CoreObject $CompareObject) {
        $thisHash = $this->hashCode();
        $compareHash = $CompareObject->hashCode();
        $areHashesEqual = $thisHash === $compareHash;
        return $areHashesEqual;
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__;
    }

}

// End CoreObject