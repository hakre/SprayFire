<?php

/**
 * @file
 * @brief Holds an interface that allows for the retrieval of data from an object
 * through array or object notation.
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
 * @copyright Copyright (c) 2011,2012 Charles Sprayberry
 */

namespace SprayFire\Config;

/**
 * @brief An interface that requires implementing objects to have data accessible
 * through array access and object notation.
 *
 * @details
 * Implementations of this interface should be immutable.  Only data passed in
 * a constructor depedency should be worked with, no new data to be set or
 * existing data to be changed or removed.
 *
 * @uses ArrayAccess
 * @uses SprayFire.Core.Structure.Overloadable
 */
interface Configuration extends \ArrayAccess, \SprayFire\Core\Structure\Overloadable {

}