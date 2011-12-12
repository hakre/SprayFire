<?php

/**
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
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace libs\sprayfire\config;

/**
 * A base configuration class that allows extending classes to get protected
 * property values and determine if those properties have been set;  Note, this
 * class will not allow calling code to set or unset the property values.
 *
 * @uses \libs\sprayfire\exceptions\UnsupportedOperationException
 * @uses \libs\sprayfire\core\CoreObject
 * @uses \libs\sprayfire\interfaces\Object
 * @uses \libs\sprayfire\interfaces\Configuration
 */
abstract class BaseConfig extends \libs\sprayfire\core\CoreObject implements \libs\sprayfire\interfaces\Configuration {

    /**
     * @param string $propertyName
     * @return mixed
     */
    public function __get($propertyName) {
        if (\property_exists($this, $propertyName)) {
            return $this->$propertyName;
        }
        return null;
    }

    /**
     * @param string $propertyName
     * @return boolean
     */
    public function __isset($propertyName) {
        if (\property_exists($this, $propertyName)) {
            return isset($this->$propertyName);
        }
        return false;
    }

    /**
     * @param string $propertyName
     * @param mixed $value
     * @throws \libs\sprayfire\exceptions\UnsupportedOperationException
     */
    public function __set($propertyName, $value) {
        throw new \libs\sprayfire\exceptions\UnsupportedOperationException('Configuration values may not be set in this object, please change the configuration file being imported.');
    }

    /**
     * @param string $propertyName
     * @throws \libs\sprayfire\exceptions\UnsupportedOperationException
     */
    public function __unset($propertyName) {
        throw new \libs\sprayfire\exceptions\UnsupportedOperationException('Configuration values may not be unset in this object, please change the configuration file being imported.');
    }

}

// End BaseConfig
