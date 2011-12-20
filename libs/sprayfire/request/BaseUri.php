<?php

/**
 * @file
 * @brief Holds a class that implements the basic functionality to split a URI
 * into its appropriate controller, action and parameter fragments.
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
     * @brief A base implementation to convert a URI into the appropriate fragments,
     * will also urldecode() the original URI passed.
     */
    class BaseUri implements \libs\sprayfire\request\Uri {

        /**
         * Holds the original, unaltered URI passed in the constructor.
         *
         * @property $originalUri
         */
        protected $originalUri;

        protected $decodedUri;

        protected $explodedUriFragments;

        protected $controller;

        protected $action;

        protected $parameters;

        /**
         * Forces the injection of a URI string into the object, this object
         * should forever be associated with the passed URI
         *
         * @param $uri string
         */
        public function __construct($uri) {
            $this->originalUri = $uri;
            $this->decodedUri = \urldecode($uri);
            $this->trimAndExplodeUri();
            $this->parseUriFragments();
            var_dump($this->parameters);
        }

        /**
         * @brief Removes the base directory from the requested URI and explodes
         * the remaining URI fragment on the URI separator, '/'.
         */
        private function trimAndExplodeUri() {
            $parsedUri = \parse_url($this->decodedUri);
            $trimPath = \preg_replace('/\/' . \basename(ROOT_PATH) . '\//', '', $parsedUri['path']);
            if (empty($trimPath)) {
                $explodedPath = array();
            } else {
                $explodedPath = \explode('/', $trimPath);
            }
            $this->explodedUriFragments = $explodedPath;
        }

        private function parseUriFragments() {
            $uriFragments = $this->explodedUriFragments;

            if (empty($uriFragments)) {
                $this->controller = \libs\sprayfire\request\Uri::DEFAULT_CONTROLLER;
                $this->action = \libs\sprayfire\request\Uri::DEFAULT_ACTION;
                $this->parameters = array();
                return;
            }

            if ($this->isParameterString($uriFragments[0])) {
                $this->controller = \libs\sprayfire\request\Uri::DEFAULT_CONTROLLER;
                $this->action = \libs\sprayfire\request\Uri::DEFAULT_ACTION;
                $this->parameters = $this->removeParameterMarker($uriFragments);
                return;
            }

            $this->controller = \array_shift($uriFragments);

            if (empty($uriFragments) || empty($uriFragments[0])) {
                $this->action = \libs\sprayfire\request\Uri::DEFAULT_ACTION;
                $this->parameters = array();
                return;
            }

            if ($this->isParameterString($uriFragments[0])) {
                $this->action = \libs\sprayfire\request\Uri::DEFAULT_ACTION;
                $this->parameters = $this->removeParameterMarker($uriFragments);
                return;
            }

            $this->action = \array_shift($uriFragments);

            if (empty($uriFragments) || empty($uriFragments[0])) {
                $this->parameters = array();
                return;
            }

            $this->parameters = $this->removeParameterMarker($uriFragments);
        }

        private function isParameterString($value) {
            $pattern = '/^:./';
            $match = \preg_match($pattern, $value);
            return (boolean) $match;
        }

        private function removeParameterMarker(array $markedParameters) {
            $unmarkedParameters = array();
            $pattern = '/^:/';
            foreach ($markedParameters as $param) {
                $unmarkedParameters[] = \preg_replace($pattern, '', $param);
            }
            return $unmarkedParameters;
        }


        /**
         * @return The action fragment of the \a $originalUri or
         *         libs.sprayfire.request.Uri::DEFAULT_ACTION
         */
        public function getAction() {
            return $this->action;
        }

        /**
         * @return The controller fragment of the \a $originalUri or
         *         libs.sprayfire.request.Uri::DEFAULT_CONTROLLER
         */
        public function getController() {
            return $this->controller;
        }

        /**
         * @return An array of params associated with \a $originalUri or an empty array
         */
        public function getParameters() {
            return $this->parameters;
        }

        /**
         * @return The original unaltered URI passed to this object
         */
        public function getRawUri() {
            return $this->originalUri;
        }

    }

    // End BaseUri

}

// End libs.sprayfire.request