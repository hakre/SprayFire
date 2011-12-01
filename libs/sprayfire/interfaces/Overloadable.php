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
 * An interface for objects that should be able to support overloaded properties.
 */
interface Overloadable {

    /**
     * Handles what is happened when an overloaded property is added to an object,
     * the key passed is the name of the property and the value is the value
     * assigned to the property upon creation.s
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function __set($key, $value);

    /**
     * Handles what happens when you try to access an overloaded property's value.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key);

    /**
     * Handles what happens when the user tries to use isset() on an overloaded
     * property.
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key);

    /**
     * Handles what happens when the user tries to unset() on an overloaded
     * property.
     *
     * @param string $key
     */
    public function __unset($key);

}

// End SF_IsOverloadable
