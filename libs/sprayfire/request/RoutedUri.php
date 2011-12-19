<?php

/**
 * @file
 * @brief Holds the interface for a Uri object that has been properly routed to a
 * mapped controller and action.
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
 * @namespace libs.sprayfire.request
 * @brief Contains all classes and interfaces needed to parse the requested URI
 * and manage the HTTP data, both headers and normal GET/POST data, that get passed
 * in each request.
 */
namespace libs\sprayfire\request {

    /**
     * @brief An extension of the Uri interface that should simply provide a means to get
     * the routed URI string along with the routed controller, action and parameters.
     */
    interface RoutedUri extends \libs\sprayfire\request\Uri {

        /**
         * Should return the completely routed URI, to include the parameters.
         *
         * @return string
         */
        public function getRoutedUri();

    }

    // End RoutedUri
}

// End libs.sprayfire.request