<?php

/**
 * @file
 * @brief The framework's implementation of the RoutedUri interface; is the object
 * returned from SprayFireRouter.
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
use libs\sprayfire\request\BaseUri as BaseUri;
use libs\sprayfire\request\RoutedUri as RoutedUri;

    /**
     * @brief Framework's implementation of a RoutedUri, provides a means to retrieve
     * a controller, action and parameters, the routed URI string and the original
     * URI string that was mapped to the routed.
     */
    class SprayFireUri extends BaseUri implements RoutedUri {

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

// End libs.sprayfire