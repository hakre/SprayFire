<?php

/**
 * @file
 * @brief Framework's implementation of an ObjectStorage data structure.
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

namespace SprayFire\Datastructs;

/**
 * @brief The framework's primary implementation to store framework objects.
 *
 * @details
 * Allows for the associating of framework objects with a key and the retrieval
 * of that object using that key.  Also allows for the removal of an object
 * associated with a key and iterating over the stored objects.
 */
class SprayFireObjectStore extends \SprayFire\Core\CoreObject implements \IteratorAggregate, \SprayFire\Datastructs\ObjectMap {

    /**
     * @brief Holds a ReflectionClass of the data type that should be implemented by objects
     * being added to this storage.
     *
     * @property ReflectionClass
     */
    protected $ReflectedParentType;

    /**
     * @brief Holds the objects being stored in this data structure
     *
     * @property array
     */
    protected $data = array();

    /**
     * @brief Will initiate the object storage as an empty array and assign the passed
     * ReflectionClass to the appropriate class property.
     *
     * @param $ReflectedObjectType ReflectionClass
     */
    public function __construct(\ReflectionClass $ReflectedObjectType) {
        $this->ReflectedParentType = $ReflectedObjectType;
    }

    /**
     * @brief Checks if the \a $Object \a exists in this data structure; returns
     * true if the object exists and false if it doesn't.
     *
     * @param $Object SprayFire.Core.Object
     * @return boolean
     */
    public function contains(\SprayFire\Core\Object $Object) {
        if ($this->indexOf($Object) === false) {
            return false;
        }
        return true;
    }

    /**
     * @brief Return the string or numeric index associated with the given object
     * or false if the object is not stored.
     *
     * @param $Object SprayFire.Core.Object
     * @return mixed \a $key \a type set or false on failure
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
     * @brief Returns whether or not this object has any objects stored in it.
     *
     * @return boolean
     */
    public function isEmpty() {
        return \count($this) <= 0;
    }

    /**
     * @brief Remove the element associated with by the \a $key \a passed.
     *
     * @param $key string
     */
    public function removeObject($key) {
        if (\array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
        }
    }

    /**
     * @brief Return the object associated with \a $key \a if it exists or
     * null if it does not.
     *
     * @param $key string
     * @return SprayFire.Core.Object
     */
    public function getObject($key) {
        if (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }

    /**
     * @brief Determines whether or not the passed \a $Object \a is a proepr
     * object for this storage type, adds the \a $Object \a if it does and
     * throws an exception if it does not.
     *
     * @details
     * This method will only throw one exception type but that exception may
     * be thrown for one of two reasons; (1) the \a $key \a is not a valid string
     * or is empty (2) the \a $Object \a does not properly implement the type
     * injected into the constructor
     *
     * @param $key A string or numeric index
     * @param $Object Should implement SprayFire.Core.Object
     * @throws InvalidArgumentException
     * @return SprayFire.Core.Object
     */
    public function setObject($key, \SprayFire\Core\Object $Object) {
        $this->throwExceptionIfKeyInvalid($key);
        $this->throwExceptionIfObjectNotParentType($Object);
        $this->data[$key] = $Object;
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

    /**
     * @param $Object SprayFire.Core.Object
     * @throws InvalidArgumentException
     */
    protected function throwExceptionIfObjectNotParentType(\SprayFire\Core\Object $Object) {
        if (!\is_object($Object) || !$this->isObjectParentType($Object)) {
            throw new \InvalidArgumentException('The value being set does not properly implement the parent type for this store.');
        }
    }

    /**
     * @brief Determines whether or not the passed \a $Object \a implements
     * the parent type set by the constructor dependency.
     *
     * @details
     * The objet is considered valid if any of the following are true:
     *
     * 1) Implements the interface passed in the constructor
     * 2) Is an instance of the class passed in the constructor
     * 3) Is a subclass of the class passed in the constructor
     *
     * @param $Object SprayFire.Core.Object
     * @return boolean
     */
    protected function isObjectParentType(\SprayFire\Core\Object $Object) {
        $isValid = false;
        $parentName = $this->ReflectedParentType->getName();
        try {
            $ReflectedObject = new \ReflectionClass($Object);
            if ($this->ReflectedParentType->isInterface()) {
                if ($ReflectedObject->implementsInterface($parentName)) {
                    $isValid = true;
                }
            } else {
                if ($ReflectedObject->getName() === $parentName || $ReflectedObject->isSubclassOf($parentName)) {
                    $isValid = true;
                }
            }
        } catch (\ReflectionException $ReflectionExc) {
            // @codeCoverageIgnoreStart
            // The possibility of this being thrown should be very, very slim as
            // a properly instantiated object must be passed, and thus must be
            // available for reflection...this is a fail safe to not leak an
            // unnecessary exception
            error_log($ReflectionExc->getMessage());
            // @codeCoverageIgnoreEnd
        }
        return $isValid;
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
     * @brief Satisfies the requirements of the Countable interface.
     *
     * @return int
     */
    public function count() {
        return \count($this->data);
    }

}