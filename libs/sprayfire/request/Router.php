<?php

/**
 * @file
 * @brief Holds the interface needed to be implemented by objects represetning
 * $_GET, $_POST and other HTTP related superglobals.
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
        public function __construct(\libs\sprayfire\config\Configuration $RoutesConfig);

        /**
         * @brief Should return a RoutedUri that is mapped off of the Uri object
         * being passed.
         *
         * @param $Uri libs.sprayfire.request.Uri
         * @return libs.sprayfire.request.RoutedUri
         */
        public function getRoutedUri(\libs\sprayfire\request\Uri $Uri);

    }

    // End Router
}

// End libs.sprayfire.request