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
use libs\sprayfire\config\Configuration as Configuration;

    /**
     * @brief Implementations should convert a libs.sprayfire.request.Uri object
     * into a libs.sprayfire.request.RoutedUri object, using values from the
     * libs.sprayfire.config.Configuration object that is passed in the constructor
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
         * @brief Requires a libs.sprayfire.config.Configuration object to be injected;
         * this object should store the necessary routing details to be able to
         * successfully create a RoutedUri.
         *
         * @param $RoutesConfig libs.sprayfire.config.Configuration
         */
        public function __construct(Configuration $RoutesConfig);

        /**
         * @brief Should return a RoutedUri that is mapped off of the Uri object
         * being passed.
         *
         * @param $Uri libs.sprayfire.request.Uri
         * @return libs.sprayfire.request.RoutedUri
         */
        public function getRoutedUri(Uri $Uri);

    }

    // End Router

// End libs.sprayfire.request