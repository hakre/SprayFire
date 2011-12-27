<?php

/**
 * @file
 * @brief Holds the interface needing to be implemented by an object representing
 * the request information.
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

namespace libs\sprayfire\request;
use libs\sprayfire\request\RoutedUri as RoutedUri;
use libs\sprayfire\request\HttpDataContainer as HttpDataContainer;

    /**
     * @brief Provides a way to pass several objects to the front controller and
     * the application components in regard to the request sent; including the
     * mapped URI controller and action as well as the PHP superglobals as objects.
     */
    interface Request {

        /**
         * @brief Provides a way to pass a variety of HTTP super global data to
         * the controller being invoked.
         *
         * @param $HttpDataContainer a libs.sprayfire.request.HttpDataContainer object
         *        holding the appropriate super globals for this request.
         */
        public function setHttpDataContainer(HttpDataContainer $HttpDataContainer);

        /**
         * @return A libs.sprayfire.request.HttpData object holding the request
         *         data
         */
        public function getHttpDataContainer();

    }

    // End Request

// End libs.sprayfire