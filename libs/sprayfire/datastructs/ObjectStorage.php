<?php

/**
 * @file
 * @brief An interface that should be implemented by data structures holding
 * SprayFire objects.
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
 * @brief Provides an API to store SprayFire derived objects, iterate over them
 * and store similar typed objects.
 *
 * @details
 * Please note that this data structure will only store classes implementing
 * the libs.sprayfire.core.Object interface.  If this interface is not implemented
 * by the objects being added an InvalidArgumentException should be thrown to
 * let the calling code know that the object is of the incorrect type.
 *
 * The only way this data structure should be manipulated and interacted with
 * is through the supplied interface; do not implement this interface through
 * the libs.sprayfire.datastructs.MutableStorage or libs.sprayfire.datastructs.ImmutableStorage
 * objects as they allow for the storing of any data type through the libs.sprayfire.datastructs.Overloadable
 * and ArrayAccess interfaces.
 */
interface ObjectStorage extends \Traversable, \Countable {

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
    public function setObject($key, \SprayFire\Core\Object $Object);

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
    public function contains(\SprayFire\Core\Object $Object);

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
    public function indexOf(\SprayFire\Core\Object $Object);

    /**
     * @return boolean
     */
    public function isEmpty();

}