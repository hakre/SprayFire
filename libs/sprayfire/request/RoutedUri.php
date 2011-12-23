<?php

/**
 * @file
 * @brief Holds the interface for a Uri object that has been properly routed to a
 * mapped controller and action.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

/**
 * @namespace libs.sprayfire.request
 * @brief Contains all classes and interfaces needed to parse the requested URI
 * and manage the HTTP data, both headers and normal GET/POST data, that get passed
 * in each request.
 */
namespace libs\sprayfire\request;
use libs\sprayfire\request\Uri as Uri;

    /**
     * @brief An extension of the libs.sprayfire.request.Uri interface to allow
     * for the setting of the original URI string used to map the RoutedUri object
     * created and a means to retrieve the mapped URI string.
     */
    interface RoutedUri extends Uri {

        /**
         * @return The URI string that was passed in this objects contructor
         */
        public function getRoutedUri();

        /**
         * @brief Provides a means to set the original URI string that this RoutedUri
         * was mapped from; this string should be returned by
         * libs.sprayfire.request.Uri::getOriginalUri().
         *
         * @param $uri string
         */
        public function setOriginalUri($uri);

    }

    // End RoutedUri

// End libs.sprayfire.request