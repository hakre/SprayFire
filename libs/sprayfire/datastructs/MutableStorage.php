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

namespace libs\sprayfire\datastructs;

use \ArrayAccess,
    libs\sprayfire\core\CoreObject,
    libs\sprayfire\interfaces\Overloadable;


/**
 * A simple data storage object that holds key/value pairs.
 *
 * This class is intended to be extended to implement the specific behavior needed
 * for that storage.  One, or more, of the four protected methods:
 *
 * get()
 * set()
 * keyHasValue()
 * removeKey()
 *
 * needs to be overriden.  For example, ImmutableStorage overrides set() and removeKey()
 * to throw \libs\sprayfire\exception\UnsupportedOperationException when the object
 * is attempting to be changed.  If you do not need to override one or more of the
 * above methods then simply use an array, as that is effectively what this object is
 * but with object notation access in addition to array notation access.
 */
abstract class MutableStorage extends CoreObject implements Overloadable, \ArrayAccess {

    /**
     * The array holding the data being stored.
     *
     * @var array
     */
    protected $data = array();

    /**
     * @param array $data
     */
    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key) {
        return $this->get($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function get($key) {
        if ($this->keyInData($key)) {
            return $this->data[$key];
        }
        return NULL;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function __isset($key) {
        return $this->keyHasValue($key);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function offsetExists($key) {
        return $this->keyHasValue($key);
    }

    /**
     * @param string $key
     * @return boolean
     */
    protected function keyHasValue($key) {
        if ($this->keyInData($key)) {
            return isset($this->data[$key]);
        }
        return false;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function __set($key, $value) {
        return $this->set($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function offsetSet($key, $value) {
        return $this->set($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function set($key, $value) {
        if ($this->keyInData($key)) {
            return $this->data[$key] = $value;
        }
        error_log('Attempting to set a value to a property that does not exist.');
        return false;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function __unset($key) {
        return $this->removeKey($key);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function offsetUnset($key) {
        return $this->removeKey($key);
    }

    /**
     * @param string $key
     */
    protected function removeKey($key) {
        if ($this->keyInData($key)) {
            unset($this->data[$key]);
            $this->data = array_values($this->data);
        }
    }

    /**
     * @param string $key
     * @return boolean
     */
    protected function keyInData($key) {
        return array_key_exists($key, $this->data);
    }

}

// End MutableStorage
