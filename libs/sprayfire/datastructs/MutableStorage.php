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
 *
 * @todo The basic get and isset functionality of this object needs to be abstracted
 * out into an abstract DataStorage class.  This DataStorage class should force the
 * implementation of the set() and removeKey() methods.  The DataStorage class should
 * implement Overloadable, ArrayAccess and Countable.
 */
abstract class MutableStorage extends \libs\sprayfire\datastructs\DataStorage {

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function set($key, $value) {
        if (is_null($key)) {
            $key = \count($this->data);
        }
        return $this->data[$key] = $value;
    }

    /**
     * @param string $key
     */
    protected function removeKey($key) {
        if ($this->keyInData($key)) {
            unset($this->data[$key]);
        }
    }

}

// End MutableStorage
