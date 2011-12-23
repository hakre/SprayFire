<?php

/**
 * @file
 * @brief An interface that should be implemented by data structures holding
 * SprayFire objects.
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
     * @brief Provides an API to store SprayFire derived objects, iterate over them
     * and store similar typed objects.
     *
     * @details
     * Please note that this data structure will only store classes implementing
     * the libs.sprayfire.core.Object interface.  If this interface is not implemented by the objects
     * being added an InvalidArgumentException should be thrown to let the calling
     * code know that the object is of the incorrect type.
     *
     * The only way this data structure should be manipulated and interacted with
     * is through the supplied interface; do not implement this interface through
     * the libs.sprayfire.datastructs.MutableStorage or libs.sprayfire.datastructs.ImmutableStorage
     * objects as they allow for the storing of any data type through the libs.sprayfire.datastructs.Overloadable
     * and ArrayAccess interfaces.
     */
    interface ObjectStorage extends \IteratorAggregate, \Countable {

        /**
         * @brief This data structure should restrict the objects stored in it to a
         * specific class or interface; the type of class or interface should be
         * passed via a ReflectionClass object.
         *
         * @param $ReflectedObjectType ReflectionClass
         * @throws InvalidArgumentException
         */
        public function __construct(\ReflectionClass $ReflectedObjectType);

        /**
         * @brief Return an Object if one exists for the given key or null.
         *
         * @param $key string
         * @return libs.sprayfire.core.Object
         */
        public function getObject($key);

        /**
         * @brief Assigns the passed \a $Object to the given \a $key if
         * the key exists the value it stores will be overwritten by the new
         * \a $Object.
         *
         * @details
         * If \a $Object does not implement the proper type passed in the class
         * constructor this method should throw an InvalidArgumentException and if
         * the object storage being implemented does not allow for the setting of
         * new or existing objects this method should throw a
         * libs.sprayfire.exceptions.UnsupportedOperationException.
         *
         * @param $key string
         * @param $Object libs.sprayfire.core.Object
         * @return void
         * @throws libs.sprayfire.exceptions.UnsupportedOperationException
         * @throws InvalidArgumentException
         */
        public function setObject($key, \libs\sprayfire\core\Object $Object);

        /**
         * @brief Returns a boolean value indicating whether the \a $Object is
         * stored.
         *
         * @details
         * libs.sprayfire.core.Object::equals() method will be used to determine
         * if the passed \a $Object is contained within this storage.
         *
         * @param $Object libs.sprayfire.core.Object
         * @return boolean true if \a $Object is stored; false if it isn't
         */
        public function contains(\libs\sprayfire\core\Object $Object);

        /**
         * @brief Remove the object associated with \a $key, there is no need
         * to return a value.
         *
         * @param $key string
         * @return void
         */
        public function removeObject($key);

        /**
         * @brief Return the index for \a $Object or false if the object does
         * not exist in the storage.
         *
         * @details
         * The value returned from this method is likely to be a string as compared
         * to a numeric index; ultimately however it will return whatever index
         * value was set for the \a $Object.
         *
         * @param $Object libs.sprayfire.core.Object
         * @return mixed
         */
        public function indexOf(\libs\sprayfire\core\Object $Object);

        /**
         * @return boolean
         */
        public function isEmpty();

    }

    // End ObjectStorage

}

// End libs.sprayfire.datastructs