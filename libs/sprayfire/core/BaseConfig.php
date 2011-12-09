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
 * @version 0.10b
 * @since 0.10b
 */

namespace libs\sprayfire\core;

/**
 * A simple, generic configuration object to be extended by framework and application
 * objects.
 */
abstract class BaseConfig extends CoreObject implements \libs\sprayfire\interfaces\Configuration {

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
     * compatibility if the constructor is changed.
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
        $this->dataStorage[$key] = $value;
        return isset($this->dataStorage[$key]);
    }

}

// End BaseConfig
