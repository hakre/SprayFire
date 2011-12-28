<?php

/**
 * @file
 * @brief An interface for classes that should have data accessible through overloaded
 * properties.
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
 * @brief An interface that forces the implementation of the magic methods that allow
 * an object to access non-existing or inaccessible properties.
 *
 * @details
 * Please note, implementing this interface does not necessarily mean that the object
 * is mutable or immutable, simply that the implementing object must do something
 * when these methods are called.
 *
 * Optional methods should throw a SprayFire.Exceptions.UnsupportedOperationException.
 *
 * @see http://php.net/manual/en/language.oop5.overloading.php
 */
interface Overloadable {

    /**
     * @param $key string
     * @return mixed
     */
    public function __get($key);

    /**
     * @param $key string
     * @param $value mixed
     * @return mixed
     * @throws SprayFire.Exceptions.UnsupportedOperationException
     */
    public function __set($key, $value);

    /**
     * @param $key string
     * @return boolean
     */
    public function __isset($key);

    /**
     * @param $key string
     * @return boolean
     * @throws SprayFire.Exceptions.UnsupportedOperationException
     */
    public function __unset($key);

}