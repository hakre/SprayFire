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
         * @property $originalUri string
         */
        protected $originalUri;

        /**
         * Holds the original URI with all special URL characeters decoded.
         *
         * @property $decodedUri string
         */
        protected $decodedUri;

        /**
         * @brief Holds an array of URI fragments exploded by the URL separator '/'
         *
         * @property $decodedUri array
         */
        protected $explodedUriFragments;

        /**
         * @brief Holds the part of the URI fragment corresponding to the controller
         *
         * @property $controller string
         */
        protected $controller;

        /**
         * @brief The part of the URI fragment corresponding to the action
         *
         * @property $action string
         */
        protected $action;

        /**
         * @brief An array holding the list of parameters or an empty array if
         * none were passed
         *
         * @property $parameters array
         */
        protected $parameters;

        /**
         * @brief Forces the injection of a URI string into the object, this object
         * should forever be associated with the passed URI.
         *
         * @details
         * This function will also assign the appropriate properties and
         *
         * @param $uri string
         */
        public function __construct($uri) {
            $this->originalUri = $uri;
            $this->decodedUri = \urldecode($uri);
            $this->explodedUriFragments = $this->trimAndExplodeDecodedUri();
            $parsedUri = $this->parseUriFragments();
            $this->controller = $parsedUri['controller'];
            $this->action = $parsedUri['action'];
            $this->parameters = $parsedUri['parameters'];
        }

        /**
         * @brief Removes the base directory from the requested URI and explodes
         * the remaining URI fragment on the URI separator, '/'; sets the array of
         */
        private function trimAndExplodeDecodedUri() {
            $parsedUri = \parse_url($this->decodedUri);
            $trimPath = \preg_replace('/\/' . \basename(ROOT_PATH) . '\//', '', $parsedUri['path']);
            if (empty($trimPath)) {
                $explodedPath = array();
            } else {
                $explodedPath = \explode('/', $trimPath);
            }
            return $explodedPath;
        }

        /**
         * @brief Will turn an array of \a $explodedUriFragments into a parsed
         * array indicating the appropriate controller, action and list of parameters.
         *
         * @details
         * If the \a $explodedUriFragments do not have any values for the controller
         * or action the libs.sprayfire.request.Uri constants appropriate for that
         * value will be returned.  The parameter key will always return with some
         * kind of array as its value.
         *
         * @return associative array with the following keys: 'controller', 'action', 'paramters
         */
        private function parseUriFragments() {
            $uriFragments = $this->explodedUriFragments;
            $parsedFragments = array();
            $controller = 'controller';
            $action = 'action';
            $parameters = 'parameters';
            if (empty($uriFragments) || empty($uriFragments[0])) {
                $parsedFragments[$controller] = \libs\sprayfire\request\Uri::DEFAULT_CONTROLLER;
                $parsedFragments[$action] = \libs\sprayfire\request\Uri::DEFAULT_ACTION;
                $parsedFragments[$parameters] = array();
                return $parsedFragments;
            }

            if ($this->isParameterString($uriFragments[0])) {
                $parsedFragments[$controller] = \libs\sprayfire\request\Uri::DEFAULT_CONTROLLER;
                $parsedFragments[$action] = \libs\sprayfire\request\Uri::DEFAULT_ACTION;
                $parsedFragments[$parameters] = $this->removeParameterMarker($uriFragments);
                return $parsedFragments;
            }

            $parsedFragments[$controller] = \array_shift($uriFragments);

            if (empty($uriFragments) || empty($uriFragments[0])) {
                $parsedFragments[$action] = \libs\sprayfire\request\Uri::DEFAULT_ACTION;
                $parsedFragments[$parameters] = array();
                return $parsedFragments;
            }

            if ($this->isParameterString($uriFragments[0])) {
                $parsedFragments[$action] = \libs\sprayfire\request\Uri::DEFAULT_ACTION;
                $parsedFragments[$parameters] = $this->removeParameterMarker($uriFragments);
                return $parsedFragments;
            }

            $parsedFragments[$action] = \array_shift($uriFragments);

            if (empty($uriFragments) || empty($uriFragments[0])) {
                $parsedFragments[$parameters] = array();
                return $parsedFragments;
            }

            $parsedFragments[$parameters] = $this->removeParameterMarker($uriFragments);

            return $parsedFragments;
        }

        /**
         * @brief Determines if the passed \a $value is a parameter string, which
         * we signify as having a colon <code>:</code> as the first character in
         * the string.
         *
         * @param $value string
         * @return boolean
         */
        private function isParameterString($value) {
            $pattern = '/^:./';
            $match = \preg_match($pattern, $value);
            return (boolean) $match;
        }

        /**
         * @brief Will remove the leading parameter marker from all values passed.
         *
         * @param $markedParameters array of parameters with possible leading colons
         * @return array of parameters with no leading colons
         */
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
         *         libs.sprayfire.request.Uri::DEFAULT_ACTION if none was passed
         */
        public function getAction() {
            return $this->action;
        }

        /**
         * @return The controller fragment of the \a $originalUri or
         *         libs.sprayfire.request.Uri::DEFAULT_CONTROLLER if none was passed
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