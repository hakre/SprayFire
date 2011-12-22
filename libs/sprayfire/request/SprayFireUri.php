<?php

/**
 * @file
 * @brief The framework's implementation of the RoutedUri interface; is the object
 * returned from SprayFireRouter.
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
     * @brief Framework's implementation of a RoutedUri, provides a means to retrieve
     * a controller, action and parameters, the routed URI string and the original
     * URI string that was mapped to the routed.
     */
    class SprayFireUri extends \libs\sprayfire\request\BaseUri implements \libs\sprayfire\request\RoutedUri {

        /**
         * The URI string passed to the constructor representing the fully routed
         * URI string.
         *
         * @property $routedUri
         */
        protected $routedUri;

        /**
         * @brief Will take a routed URI string and then set the appropriate URI
         * properties based on the parsing of that string.
         *
         * @param $uri The routed URI string
         */
        public function __construct($uri) {
            $this->routedUri = $uri;
            $decodedUri = \urldecode($uri);
            $uriFragments = $this->trimAndExplodeUri($decodedUri);
            $parsedUri = $this->parseUriFragments($uriFragments);
            $this->setProperties($parsedUri);
        }

        /**
         * @param $uri The original string used to map the routed URI
         */
        public function setOriginalUri($uri) {
            $this->originalUri = $uri;
        }

        /**
         * @return The routed URI string passed to the constructor
         */
        public function getRoutedUri() {
            return $this->routedUri;
        }

    }

    // End SprayFireUri
}

// End libs.sprayfire