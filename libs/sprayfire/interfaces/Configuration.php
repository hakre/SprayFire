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
 * An interface for configuration objects used by the application and framework.
 */
interface Configuration {

    /**
     * Ensures that there are no dependencies needed when the configuration objects
     * are created.
     */
    public function __construct();

    /**
     * The $key passed will be searched for in some data structure holding key/value
     * pairs, if the key is found the value associated with it should be returned
     * otherwise the method should return NULL.
     *
     * @param string $key
     * @return mixed Whatever value is associated with the key
     */
    public function read($key);

    /**
     * Will add a new, or replace an existing, entry in the data structure holding
     * the key/value pairs, should return a status as to whether or not the $value
     * was written to $key.
     *
     * @param string $key
     * @param mixed $value
     * @return boolean True/false if the $value was written to $key
     */
    public function write($key, $value);

}

// End Configuration
