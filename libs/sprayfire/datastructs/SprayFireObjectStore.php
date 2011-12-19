<?php

/**
 * @file
 * @brief Framework's implementation of an ObjectStorage data structure.
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
     * @brief The framework's primary implementation to store framework objects.
     *
     * @details
     * It allows for the associating of framework objects with a key and the retrieval
     * of that object using that key.  The data structure also allows for the removal
     * of objects associated with a key and iterating over the objects stored in the
     * array.
     */
    class SprayFireObjectStore extends MutableIterator implements \libs\sprayfire\datastructs\ObjectStorage {

        /**
         * @brief Holds a ReflectionClass of the data type that should be implemented by objects
         * being added to this storage.
         *
         * @property ReflectionClass
         */
        protected $ReflectedParentType;

        /**
         * @brief Will initiate the object storage as an empty array and assign the passed
         * ReflectionClass to the appropriate class property.
         *
         * @param $ReflectedObjectType A ReflectionClass object
         */
        public function __construct(\ReflectionClass $ReflectedObjectType) {
            parent::__construct(array());
            $this->ReflectedParentType = $ReflectedObjectType;
        }

        /**
         * @brief Will check if the storage has a bucket holding the passed object and return
         * true if the object exists and false if it doesn't.
         *
         * @param $Object libs.sprayfire.core.Object
         * @return boolean
         */
        public function contains(\libs\sprayfire\core\Object $Object) {
            if ($this->indexOf($Object) === false) {
                return false;
            }
            return true;
        }

        /**
         * @brief Will return the string or numeric index associated with the given object or
         * false if the object is not stored.
         *
         * @param $Object libs.sprayfire.core.Object
         * @return mixed \a $key \a type set or false on failure
         */
        public function indexOf(\libs\sprayfire\core\Object $Object) {
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
         * @brief Will return the object associated with the key, if that key exists, or return
         * null.
         *
         * @param $key string
         * @return libs.sprayfire.core.Object
         */
        public function getObject($key) {
            return $this->get($key);
        }

        /**
         * @brief Hooks into the DataStorage set method to complete the requirements
         * of the libs.sprayfire.core.ObjectStorage interface.
         *
         * @details
         * Will assign the passed object to the specified key, notice that this may
         * result in the overwriting of an existing key.
         *
         * @param $key string The index for this object
         * @param $Object libs.sprayfire.core.Object to be added
         * @throws InvalidArgumentException
         * @return libs.sprayfire.core.Object
         */
        public function setObject($key, \libs\sprayfire\core\Object $Object) {
            return $this->set($key, $Object);
        }

        public function removeObject($key) {
            if (array_key_exists($key, $this->data)) {
                unset($this->data[$key]);
                $this->skipNextIteration = true;
            }
        }

        /**
         * @brief Determines whether or not the passed \a $value \a is a proepr
         * object for this storage type, adds the \a $value \a if it does and
         * throws an exception if it does not.
         *
         * @details
         * We are not type hinting \a $value \a in this function so that we are
         * properly overriding the parent function; throwing an exception if the
         * value is not the correct type will handle this "break" in type hinting.
         * Furthermore the only method that should be invoking this is setObject
         * as the other methods of accessing an overloadable project have been
         * overriden.
         *
         * @param $key A string or numeric index
         * @param $value Should implement libs.sprayfire.core.Object
         * @throws InvalidArgumentException
         * @return libs.sprayfire.core.Object
         * @see libs.sprayfire.datastructs.MutableStorage
         */
        protected function set($key, $value) {
            $this->throwExceptionIfKeyInvalid($key);
            $this->throwExceptionIfObjectNotParentType($value);
            return parent::set($key, $value);
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
         * @param $Object libs.sprayfire.core.Object
         * @throws InvalidArgumentException
         */
        protected function throwExceptionIfObjectNotParentType(\libs\sprayfire\core\Object $Object) {
            if (!\is_object($Object) || !$this->isObjectParentType($Object)) {
                throw new \InvalidArgumentException('The value being set does not properly implement the parent type for this store.');
            }
        }

        /**
         * Determines whether or not the passed \a $Object \a implements the parent
         * type set by the constructor dependency.
         *
         * 1) Implements the interface passed in the constructor
         * 2) Is an instance of the class passed in the constructor
         * 3) Is a subclass of the class passed in the constructor
         *
         * @param $Object libs.sprayfire.core.Object
         * @return boolean
         */
        protected function isObjectParentType(\libs\sprayfire\core\Object $Object) {
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
                error_log($ReflectionExc->getMessage());
                // @codeCoverageIgnoreEnd
            }
            return $isValid;
        }

        // *********************************************************************
        // *********************************************************************
        // The below methods are overriden to adhere to the true contract of the
        // libs.sprayfire.datastructs.ObjectStorage interface without having to do
        // a runtime check to see if the set value properly implements the
        // libs.sprayfire.core.Object interface.
        // *********************************************************************
        // *********************************************************************

        /**
         * @brief Overridden to throw an exception as we only want objects being
         * stored in this data structure to be retrieved via getObject()
         *
         * @param $key string
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        public function __get($key) {
            throw new \libs\sprayfire\exceptions\UnsupportedOperationException('This method of adding an object is not supported; please use getObject()');
        }

        /**
         * @brief Overridden to throw an exception as we only want objects being
         * stored in this data structure to be retrieved via getObject()
         *
         * @param $key string
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        public function offsetGet($key) {
            throw new \libs\sprayfire\exceptions\UnsupportedOperationException('This method of adding an object is not supported; please use getObject()');
        }

        /**
         * @brief Overridden to throw an exception as we only want objects being
         * stored in this data structure via setObject()
         *
         * @param $key string
         * @param $value mixed
         */
        public function __set($key, $value) {
            throw new \libs\sprayfire\exceptions\UnsupportedOperationException('This method of setting an object is not supported; please use setObject()');
        }

        /**
         * @brief Overridden to throw an exception as we only want objects being
         * stored in this data structure via setObject()
         *
         * @param $key string
         * @param $value mixed
         */
        public function offsetSet($key, $value) {
            throw new \libs\sprayfire\exceptions\UnsupportedOperationException('This method of setting an object is not supported; please use setObject()');
        }

        /**
         * @brief Overriden to throw an exception as only want the property to be
         * checked for existence via other implemented means, such as contains()
         * and indexOf().
         *
         * @param $key string
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        public function __isset($key) {
            throw new \libs\sprayfire\exceptions\UnsupportedOperationException('This method of determining an object\'s existence in the data storage is not supported; please contains() ');
        }

        /**
         * @brief Overriden to throw an exception as only want the property to be
         * checked for existence via other implemented means, such as contains()
         * and indexOf().
         *
         * @param $key string
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        public function offsetExists($key) {
            throw new \libs\sprayfire\exceptions\UnsupportedOperationException('This method of determining an object\'s existence in the data storage is not supported; please contains() ');
        }

        /**
         * @brief Overridden to throw an exception as we only want properties to
         * be removed from this data structure through removeObject().
         *
         * @param $key string
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        public function __unset($key) {
            throw new \libs\sprayfire\exceptions\UnsupportedOperationException('This method of removing an object from storage is not supported; please use removeObject()');
        }

        /**
         * @brief Overridden to throw an exception as we only want properties to
         * be removed from this data structure through removeObject().
         *
         * @param $key string
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         */
        public function offsetUnset($key) {
            throw new \libs\sprayfire\exceptions\UnsupportedOperationException('This method of removing an object from storage is not supported; please use removeObject()');
        }

    }

    // End SprayFireObjectStore

}

// End libs.sprayfire.core