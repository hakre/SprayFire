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
 * A simple, generic configuration object to be extended by framework and application
 * objects.
 */
abstract class SF_BaseConfig implements SF_IsConfigurationStorage {

    /**
     * The data structure holding the key value pairs.
     *
     * @var array
     */
    private $dataStorage = array();

    /**
     * Nothing is happening in the constructor, feel free to override in children
     * classes.
     *
     * It is advised that you add a call to parent::__construct() to ensure future
     * compatibility if the constructor is overriden.
     */
    public function __construct() {

    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function read($key) {
        if (!isset($this->dataStorage[$key])) {
            return NULL;
        }
        return $this->dataStorage[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function write($key, $value) {
        $dataStorage[$key] = $value;
        return isset($this->dataStorage[$key]);
    }

}

// End SF_BaseConfig
