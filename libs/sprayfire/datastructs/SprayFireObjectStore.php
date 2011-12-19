<?php

/**
 * @file
 * @brief 
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

namespace libs\sprayfire\datastructs {

    /**
     * This is the framework's primary data structure to store framework objects.
     *
     * It allows for the associating of framework objects with a key and the retrieval
     * of that object using that key.  The data structure also allows for the removal
     * of objects associated with a key and iterating over the objects stored in the
     * array.
     */
    class SprayFireObjectStore extends MutableIterator implements \libs\sprayfire\datastructs\ObjectStorage {

        /**
         * Holds a ReflectionClass of the data type that should be implemented by objects
         * being added to this storage.
         *
         * @property ReflectionClass
         */
        protected $ReflectedParentType;

        /**
         * @brief Holds a ReflectionClass of the framework's Object interface, used to ensure
         * all objects added to this store properly implement this interface.
         *
         * @property ReflectionClass
         */
        private $ReflectedFrameworkObject;

        /**
         * @brief Will initiate the object storage as an empty array and assign the passed
         * ReflectionClass to the appropriate class property.
         *
         * @param $ReflectedObjectType A ReflectionClass object
         */
        public function __construct(\ReflectionClass $ReflectedObjectType) {
            parent::__construct(array());
            $this->ReflectedParentType = $ReflectedObjectType;
            $this->ReflectedFrameworkObject = new \ReflectionClass("\\libs\\sprayfire\\core\\Object");
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

        /**
         * @brief Determines whether or not the passed \a $value \a is a proepr
         * object for this storage type, adds the \a $value \a if it does and
         * throws an exception if it does not.
         *
         * @details
         * We are not type hinting \a $value \a in this function so that we are
         * properly overriding the parent function; throwing an exception if the
         * value is not the correct type will handle this "break" in type hinting.
         *
         * @param $key A string or numeric index
         * @param $value Should implement libs.sprayfire.core.Object
         * @throws InvalidArgumentException
         * @return libs.sprayfire.core.Object
         */
        protected function set($key, $value) {
            // We are throwing an exception if the value is not a framework object to
            // adhere to the contract of the ObjectStorage interface.  A crafty developer
            // would soon discover that __set() and offsetSet do not have type hints on
            // them and any object type could be added.  Ultimately SprayFire implemented
            // data structures expect SprayFire objects to be stored in them.
            $this->throwExceptionIfNotFrameworkObject($value);
            $this->throwExceptionIfObjectNotParentType($value);
            return parent::set($key, $value);
        }

        /**
         * @param $Object mixed
         * @throws InvalidArgumentException
         */
        protected function throwExceptionIfNotFrameworkObject($Object) {
            if (!$this->isFrameworkObject($value)) {
                throw new \InvalidArgumentException('Objects added to this storage MUST implement the Object interface.');
            }
        }

        /**
         * @brief Simple check to determine if the \a $Object \a passed properly
         * implements the framework's libs.sprayfire.core.Object interface.
         *
         * @details
         * Here to ensure that the object being added to the store is a proper
         * framework object; this has to be checked as there is no type hinting for
         * the __set and offsetSet methods in the parent classes and thus any object
         * type could theoreticaly be added.
         *
         * We are checking this when objects are set as it would be impossible to tell
         * from the passed ReflectionClass if the type properly implements the Object
         * interface as the type may itelf be an interface that does not extend the
         * Object interface.
         *
         * Furthermore, we are making this check and enforcement of only adding
         * framework objects because the methods in this data storage are expecting
         * the classes to implement this interface; specifically the indexOf()
         * method.
         *
         * @param $Object mixed
         * @return boolean
         */
        protected function isFrameworkObject($Object) {
            $frameworkObjectInterface = $this->ReflectedFrameworkObject->getName();
            try {
                $ReflectedObject = new \ReflectionClass($Object);
                if ($ReflectedObject->implementsInterface($frameworkObjectInterface)) {
                    return true;
                }
            } catch (\ReflectionException $ReflectExc) {
                // no need to do anything here, if an exception is thrown then the value
                // passed obviously cannot be a framework object.
            }

            return false;
        }

        /**
         * @param $Object mixed
         * @throws InvalidArgumentException
         */
        protected function throwExceptionIfObjectNotParentType($Object) {
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
        protected function isObjectParentType($Object) {
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

    }

    // End SprayFireObjectStore

}

// End libs.sprayfire.core