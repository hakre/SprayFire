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
abstract class SF_CoreObject implements SF_IsOverloadable {

    /**
     * An array of overloaded properties with the property name equaling a key in
     * the array and the value stored by that key as the value of the property.
     *
     * @var array
     */
    private $overloadedProperties = array();

    /**
     * Will add the $key and $value to the overloaded properties data structure.
     *
     * @param type $key
     * @param type $value
     * @return boolean
     */
    public function __set($key, $value) {
        $this->overloadedProperties[$key] = $value;
        $isPropertySet = isset($this->overloadedProperties[$key]);
        return $isPropertySet;
    }

    /**
     * Will determine if the $key exists in the overloaded properties in the data
     * structure, if it does will return the value associated with that key.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key) {
        $doesPropertyExist = array_key_exists($key, $this->overloadedProperties);
        if ($doesPropertyExist) {
            return $this->overloadedProperties[$key];
        }
        return NULL;
    }

    /**
     * Determines whether the property has been overloaded and has a value associated
     * with it.
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key) {
        $doesPropertyExist = array_key_exists($key, $this->overloadedProperties);
        if (!$doesPropertyExist) {
            return false;
        }
        $propertyValue = $this->overloadedProperties[$key];
        $isPropertySet = isset($propertyValue);
        return $isPropertySet;
    }

    /**
     * Removes the passed key from the overloaded properties data structure, if it
     * exists.
     *
     * @param string $key
     */
    public function __unset($key) {
        $doesPropertyExist = array_key_exists($key, $this->overloadedProperties);
        if (!$doesPropertyExist) {
            return;
        }
        $newArray = array();
        foreach ($this->overloadedProperties as $property => $value) {
            if ($property !== $key) {
                $newArray[$property] = $value;
            }
        }
        $this->overloadedProperties = $newArray;
    }

}

// End SF_CoreObject