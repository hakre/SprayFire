<?php

/**
 * @file
 * @brief Holds the interface needed to be implemented by objects represetning
 * $_GET, $_POST and other HTTP related superglobals.
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
 * @brief Implementations should convert a libs.sprayfire.request.Uri object
 * into a libs.sprayfire.request.RoutedUri object, using values from the
 * SprayFire.Config.Configuration object that is passed in the constructor
 * as defaults if needed.
 *
 * @details
 * Implementations of this interface should not necessarily be responsible for
 * the actual instantiation and invocation of the necessary controller objects.
 * That responsibility is left to a different object that is better suited for
 * the task.  Implementing classes should simply provide a RoutedUri object
 * that can be used by the actual dispatching class.
 *
 * @see https://github.com/cspray/SprayFire/wiki/Routing
 */
interface Router {

    /**
     * @brief Should return a RoutedUri that is mapped off of the Uri object
     * being passed.
     *
     * @param $Uri SprayFire.Request.Uri
     * @return SprayFire.Request.RoutedUri
     */
    public function getRoutedUri(\SprayFire\Request\Uri $Uri);

}