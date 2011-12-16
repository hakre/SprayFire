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

namespace libs\sprayfire\interfaces;

use \ArrayAccess,
    \ReflectionClass,
    \Iterator,
    libs\sprayfire\interfaces\Object,
    libs\sprayfire\interfaces\Overloadable;

/**
 * Provides a data structure to store *framework* derived objects.
 *
 * Please note that this data structure will only store classes implementing the
 * \libs\sprayfire\interfaces\Object interface.  If this interface is not implemented
 * by the objects being added an \InvalidArgumentException should be thrown to let
 * the calling code know that the object is of the incorrect type.
 *
 * Also, please note that this object provides a variety of means to store and gain
 * access to the objects, including:
 *
 * 1) via object notation '->' *framework preferred
 * 2) via array notation '[]'
 * 3) via `getObject()` and `setObject()` methods
 */
interface ObjectStorage extends \ArrayAccess, Overloadable, Iterator {

    /**
     * This storage object should restrict the objects stored in it to a specific
     * class or interface; the type of class or interface should be passed via a
     * \ReflectionClass object.
     *
     * @param \ReflectionClass $ReflectedObjectType
     * @throws \InvalidArgumentException
     */
    public function __construct(\ReflectionClass $ReflectedObjectType);

    /**
     * Will return an Object if one exists for the given key or null.
     *
     * @param string $key
     * @return mixed
     */
    public function getObject($key);

    /**
     * Will assign the passed Object to the passed $key; should return some boolean
     * indicator as to whether the object was set to the given $key.
     *
     * If Object does not implement the proper type passed in the class constructor
     * this method should throw an \InvalidArgumentException and if the object
     * storage being implemented does not allow for the setting of new or existing
     * objects this method should throw a \libs\sprayfire\exceptions\UnsupportedOperationException.
     *
     * @param string $key
     * @param \libs\sprayfire\interfaces\Object $Object
     * @return boolean
     * @throws \libs\sprayfire\exceptions\UnsupportedOperationException
     * @throws \InvalidArgumentException
     */
    public function setObject($key, Object $Object);

    /**
     * Will return a boolean value if the passed Object is stored; the Object::equals()
     * method will be used to determine if the passed Object is contained within
     * this store.
     *
     * @param \libs\sprayfire\interfaces\Object $Object
     * @return boolean
     */
    public function contains(Object $Object);

    /**
     * Will return the index for the given index or false if the object does
     * not exist in the store.  Note, this may not necessarily be a numeric index
     * but instead a string index.
     *
     * @param \libs\sprayfire\interfaces\Object $Object
     * @return mixed
     */
    public function indexOf(Object $Object);

}

// End ObjectStorage
