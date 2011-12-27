<?php

/**
 * @file
 * @brief Provides a generic means to store data through object (->) or array ([])
 * notation.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace libs\sprayfire\datastructs;

/**
 * @brief Stores data in a property and allows access to that data via object
 * or array notation.
 *
 * @details
 * This class provides a generic means to retrieve data based on a unique
 * \a $key and determine if that \a $key has a non-null element associated
 * with it.  Extending classes should provide the exact implementation details
 * for how that \a $key and \a $value should be added to the appropriate property.
 *
 * To provide an easy way to provide both object and array notation access there
 * are four protected methods that can be considered 'hooks', implementing or
 * overriding the following methods will change the way this object works.
 *
 * <pre>
 * Hook name           | Method's effected
 * -------------------------------------------------------------------------
 * set($key, $value)   | __set($key, $value), offsetSet($key, $value)
 * -------------------------------------------------------------------------
 * get($key)           | __get($key), offsetGet($key)
 * -------------------------------------------------------------------------
 * keyHasValue($key)   | __isset($key), offsetExists($key)
 * -------------------------------------------------------------------------
 * removeKey($key)     | __unset($key), offsetUnset($key)
 * </pre>
 *
 * If you extend this class it should be expected that the object gets and
 * sets data values via object or array notation.  If methods are also used
 * to manipulate the data structure you should really evaluate whether or not
 * this is the best option as you will still have to take into account the
 * public methods that allow the object an array notation.
 */
abstract class DataStorage extends \libs\sprayfire\core\CoreObject implements \ArrayAccess, \Countable, \IteratorAggregate, \libs\sprayfire\datastructs\Overloadable {

    /**
     * An array holding the data being stored.
     *
     * @property $data
     */
    protected $data = array();

    /**
     * @brief Should accept an array that has the information to store, or
     * an empty array if an empty structure is to be created.
     *
     * @details
     * If an associative indexed array is passed it is recommended that you
     * only set string properties and do not use the structure as an array
     * with numerically indexed keys.  However, the opposite applies if the
     * structure is to hold numerically indexed keys work with it as an array
     * and only store numerically indexed keys in it.
     *
     * @param $data array
     */
    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * @param $key string
     * @return mixed
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     * @param $key string
     * @return mixed
     */
    public function offsetGet($key) {
        return $this->get($key);
    }

    /**
     * @brief 'hook' to retrieve the data associated with a given key.
     *
     * @details
     * If your class needs to change the way object are retrieved from the data
     * store simply override this function, ensuring that the proper value is
     * returned.
     *
     * @param $key string
     * @return mixed
     */
    protected function get($key) {
        if ($this->keyInData($key)) {
            return $this->data[$key];
        }
        return NULL;
    }

    /**
     * @param $key string
     * @return boolean
     */
    public function __isset($key) {
        return $this->keyHasValue($key);
    }

    /**
     * @param $key string
     * @return boolean
     */
    public function offsetExists($key) {
        return $this->keyHasValue($key);
    }

    /**
     * @brief 'hook' to determine if a given \a $key exists and has a value
     * associated with it.
     *
     * @param $key string
     * @return boolean
     */
    protected function keyHasValue($key) {
        if ($this->keyInData($key)) {
            return isset($this->data[$key]);
        }
        return false;
    }

    /**
     * @brief An internally used method to determine if a given \a $key exists
     * in the stored data.
     *
     * @details
     * If your class needs to override this method please ensure a true boolean
     * type is returned.
     *
     * @param $key string
     * @return boolean
     */
    protected function keyInData($key) {
        return \array_key_exists($key, $this->data);
    }

    /**
     * @param $key string
     * @param $value mixed
     * @return mixed
     */
    public function __set($key, $value) {
        return $this->set($key, $value);
    }

    /**
     * @param $key string
     * @param $value mixed
     * @return mixed
     */
    public function offsetSet($key, $value) {
        return $this->set($key, $value);
    }

    /**
     * @param $key string
     * @return boolean
     */
    public function __unset($key) {
        return $this->removeKey($key);
    }

    /**
     * @param $key string
     * @return boolean
     */
    public function offsetUnset($key) {
        return $this->removeKey($key);
    }

    /**
     * Required by the Countable interface, will return the number of data elements
     * stored in $data; please note that this is not a recursive count.
     *
     * @return int
     */
    public function count() {
        return \count($this->data);
    }

    public function getIterator() {
        return new \ArrayIterator($this->data);
    }

    /**
     * @brief 'hook' to set the given \a $value for the associated \a $key.
     *
     * @details
     * Please return the value set or false if there was an error.
     *
     * @param $key string
     * @param $value mixed
     * @return mixed
     * @throws libs.sprayfire.exceptions.UnsupportedOperationException
     */
    abstract protected function set($key, $value);

    /**
     * @brief 'hook' to remove the given \a $key from the stored data.
     *
     * @param $key string
     */
    abstract protected function removeKey($key);

}