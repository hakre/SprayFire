<?php

/**
 * @file
 * @brief Provides a generic means to store data through object (->) or array ([])
 * notation.
 *
 * @details
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
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

/**
 * @namespace libs.sprayfire.datastructs
 * @brief Holds the API used by the framework to store and transfer sets of data.
 */
namespace libs\sprayfire\datastructs {

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
     * Hook name           | Method's effected
     * -------------------------------------------------------------------------
     * set($key, $value)   | __set($key, $value), offsetSet($key, $value)
     * -------------------------------------------------------------------------
     * get($key)           | __get($key), offsetGet($key)
     * -------------------------------------------------------------------------
     * keyHasValue($key)   | __isset($key), offsetExists($key)
     * -------------------------------------------------------------------------
     * removeKey($key)     | __unset($key), offsetUnset($key)
     */
    abstract class DataStorage extends \libs\sprayfire\core\CoreObject implements \ArrayAccess, \Countable, \libs\sprayfire\datastructs\Overloadable {

        /**
         * The array holding the data being stored.
         *
         * @property array
         */
        protected $data = array();

        /**
         * Should accept an array, associative or numeric indexed, that stores the
         * information for this object.
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
         * Is the framework 'hook' to retrieve the data associated with a given key;
         * all methods that retrieve a value from the data array should call this
         * method instead.
         *
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
         * Is used by methods needing to determine if the given $key is part of the
         * elements being stored and the key has a value associated with it; all methods
         * needing to emulate `isset` functionality should call this method.
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
         * Is the method used by this class to determine whether or not the key exists.
         *
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

        /**
         * Should set the value in the data store associated with the passed key or
         * a libs.sprayfire.exceptions.UnsupportedOperationException should be thrown
         * if the class does not allow data to be set after __construct().
         *
         * Please return the value set or false if there was an error.
         *
         * @param $key string
         * @param $value mixed
         * @return mixed
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        abstract protected function set($key, $value);

        /**
         * Should remove the value associated with the passed key from the data store;
         * no value needs to be returned, simply ensure that the count for the data
         * store is properly recalibrated.
         *
         * @param string $key
         */
        abstract protected function removeKey($key);

    }

    // End DataStorage

}

// End libs.sprayfire.datastructs