<?php

/**
 * @file
 * @brief An interface representing a basic framework object, used as a means to
 * ensure some very basic, common object functionality.
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

namespace SprayFire\Core;

/**
 * @brief An interface for basic framework objects.
 *
 * @details
 * Primarily this interface is used to ensure that there is a way to
 *
 * - represent a class as a string, for debugging purposes if necessary or
 * for pure data objects and easy output
 * - compare 2 objects to see if they are equal to each other based on a means
 * that makes sense for the given object
 * - return a unique identifier for the given object
 */
interface Object {

    /**
     * @brief Compare two objects to see if they are equal to each other.
     *
     * @param $Object libs.sprayfire.core.Object
     * @return boolean
     */
    public function equals(\SprayFire\Core\Object $Object);

    /**
     * @return A unique identifier for the given object
     */
    public function hashCode();

    /**
     * @return A string representation for the object
     */
    public function __toString();

}