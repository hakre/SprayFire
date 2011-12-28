<?php

/**
 * @file
 * @brief Framework object to allow working with PHP superglobals, or any array,
 * as an object; changes to the array change the object and changes to the object
 * change the array.
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

namespace SprayFire\Request;

/**
 * @brief Will allow for an array to be passed, the values of that array to be
 * treated as an object, and for changes to the array or the object to make
 * changes to the other.
 */
abstract class HttpData extends \SprayFire\Datastructs\MutableStorage {

    /**
     * @param $data An array passed by reference
     */
    public function __construct(array &$data) {
        $this->data =& $data;
    }

}