<?php

/**
 * @file
 * @brief Holds the interface needing to be implemented by an object representing
 * the request information.
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
     * @brief Provides a way to pass several objects to the front controller and
     * the application components in regard to the request sent; including the
     * mapped URI controller and action as well as the PHP superglobals as objects.
     */
    interface Request {

        /**
         * @param $RoutedUri A libs.sprayfire.request.RoutedUri object that holds
         *        the controller, action and parameters to invoke for this request.
         */
        public function __construct(\libs\sprayfire\request\RoutedUri $RoutedUri);

        /**
         * @brief Provides a way to pass a variety of HTTP super global data to
         * the controller being invoked.
         *
         * @param $HttpDataContainer a libs.sprayfire.request.HttpDataContainer object
         *        holding the appropriate super globals for this request.
         */
        public function setHttpDataContainer(\libs\sprayfire\request\HttpDataContainer $HttpDataContainer);

        /**
         * @return A libs.sprayfire.request.HttpData object holding the request
         *         data
         */
        public function getHttpDataContainer();

    }

    // End Request
}

// End libs.sprayfire