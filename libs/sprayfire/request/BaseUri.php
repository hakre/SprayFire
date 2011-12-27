<?php

/**
 * @file
 * @brief Holds a class that implements the basic functionality to split a URI
 * into its appropriate controller, action and parameter fragments.
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
use libs\sprayfire\core\CoreObject as CoreObject;
use libs\sprayfire\request\Uri as Uri;

/**
 * @brief A base implementation to convert a URI into the appropriate fragments,
 * will also urldecode() the original URI passed.
 */
class BaseUri extends CoreObject implements Uri {

    /**
     * Holds the original, unaltered URI passed in the constructor.
     *
     * @property $originalUri string
     */
    protected $originalUri;

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
        $decodedUri = \urldecode($uri);
        $uriFragments = $this->trimAndExplodeUri($decodedUri);
        $parsedUri = $this->parseUriFragments($uriFragments);
        $this->setProperties($parsedUri);
    }

    /**
     * @brief Will remove leading and forward '/' and the root install directory
     * and return a 0-index array containing the remaining fragments.
     *
     * @return array in controller, action, param1, param2, paramN order, the
     *         keys should be properly numerically index.
     */
    protected function trimAndExplodeUri($uri) {
        $parsedUri = \parse_url($uri);
        $path = $parsedUri['path'];
        $path = \trim($path, '/');
        $explodedPath = \explode('/', $path);
        if ($explodedPath[0] === \basename(\ROOT_PATH)) {
            unset($explodedPath[0]);
        }
        return \array_values($explodedPath);
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
    protected function parseUriFragments($uriFragments) {
        $parsedFragments = array();
        $controller = 'controller';
        $action = 'action';
        $parameters = 'parameters';

        if (empty($uriFragments) || empty($uriFragments[0])) {
            $parsedFragments[$controller] = Uri::DEFAULT_CONTROLLER;
            $parsedFragments[$action] = Uri::DEFAULT_ACTION;
            $parsedFragments[$parameters] = array();
            return $parsedFragments;
        }

        if ($this->isParameterString($uriFragments[0])) {
            $parsedFragments[$controller] = Uri::DEFAULT_CONTROLLER;
            $parsedFragments[$action] = Uri::DEFAULT_ACTION;
            $parsedFragments[$parameters] = $this->removeParameterMarker($uriFragments);
            return $parsedFragments;
        }

        $parsedFragments[$controller] = \array_shift($uriFragments);

        if (empty($uriFragments) || empty($uriFragments[0])) {
            $parsedFragments[$action] = Uri::DEFAULT_ACTION;
            $parsedFragments[$parameters] = array();
            return $parsedFragments;
        }

        if ($this->isParameterString($uriFragments[0])) {
            $parsedFragments[$action] = Uri::DEFAULT_ACTION;
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
     * we signify as having a colon <code>:</code> in the first character of
     * the string.
     *
     * @param $value string
     * @return boolean
     */
    protected function isParameterString($value) {
        if (\substr($value, 0, 1) === ':') {
            return true;
        }
        return false;
    }

    /**
     * @brief Will remove the leading parameter marker from all values passed.
     *
     * @param $parameters array of parameters with possible leading colons
     * @return array of parameters with no leading colons
     */
    protected function removeParameterMarker(array $parameters) {
        $unmarkedParameters = array();
        foreach ($parameters as $param) {
            if ($this->isParameterString($param)) {
                $unmarkedParameters[] = \substr($param, 1);
            } else {
                $unmarkedParameters[] = $param;
            }
        }
        return $unmarkedParameters;
    }

    /**
     * @brief Will set the appropriate properties value based on the \a $properties
     * passed.
     *
     * @param $properties associative index array with 'controller, 'action' and 'parameters' keys
     */
    protected function setProperties(array $properties) {
        $this->controller = $properties['controller'];
        $this->action = $properties['action'];
        $this->parameters = $properties['parameters'];
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
    public function getOriginalUri() {
        return $this->originalUri;
    }

}