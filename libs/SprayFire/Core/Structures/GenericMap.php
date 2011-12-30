<?php

/**
 * @file
 * @brief Provides a very basic implementation that allows for the storing of any
 * SprayFire.Core.Object passed.
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
namespace SprayFire\Core\Structures;

/**
 * @brief
 */
class GenericMap extends \SprayFire\Core\CoreObject implements \IteratorAggregate, \SprayFire\Core\Structures\ObjectMap {

    /**
     * @brief Holds the objects being stored in this data structure
     *
     * @property $data
     */
    protected $data = array();

    /**
     * @param $Object SprayFire.Core.Object to be stored in Map
     * @return True if Map stores \a $Object or false if it doesn't
     */
    public function contains(\SprayFire\Core\Object $Object) {
        if ($this->indexOf($Object) === false) {
            return false;
        }
        return true;
    }

    /**
     * @return An integer representing the number of objects stored
     */
    public function count() {
        return \count($this->data);
    }

    /**
     * @param $key A string associated with a given SprayFire.Core.Object stored
     * @return A SprayFire.Core.Object or null if the object does not exist
     */
    public function getObject($key) {
        if (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }

    /**
     * @brief Return the stringassociated with the given object or false if the
     * object is not stored.
     *
     * @param $Object SprayFire.Core.Object
     * @return mixed \a $key associated with \a $Object or false on failure
     */
    public function indexOf(\SprayFire\Core\Object $Object) {
        $index = false;
        foreach ($this->data as $key => $StoredObject) {
            if ($Object->equals($StoredObject)) {
                $index = $key;
                break;
            }
        }
        return $index;
    }

    /**
     * @return true if the Map has no elements and false if it does
     */
    public function isEmpty() {
        return \count($this) <= 0;
    }

    /**
     * @param $key A string associated with a key
     */
    public function removeObject($key) {
        if (\array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
        }
    }

    /**
     * @param $key string
     * @param $Object SprayFire.Core.Object to store in the Map
     * @throws InvalidArgumentException
     */
    public function setObject($key, \SprayFire\Core\Object $Object) {
        $this->throwExceptionIfKeyInvalid($key);
        $this->data[$key] = $Object;
    }

    /**
     * @brief Satisfies the requirements of the IteratorAggregate interface
     *
     * @return ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->data);
    }

    /**
     * @param $key string
     * @throws InvalidArgumentException
     */
    protected function throwExceptionIfKeyInvalid($key) {
        if (empty($key) || !\is_string($key)) {
            throw new \InvalidArgumentException('The key for an object may not be an empty or non-string value.');
        }
    }

}