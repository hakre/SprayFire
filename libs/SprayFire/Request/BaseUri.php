<?php

/**
 * @file
 * @brief Holds a class that implements the basic functionality to split a URI into
 * its appropriate controller, action and parameter fragments.
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

namespace SprayFire\Request;

/**
 * @brief A base implementation to convert a URI into the appropriate fragments,
 * will also urldecode() the original URI passed.
 *
 * @uses SprayFire.Request.Uri
 * @uses SprayFire.Core.CoreObject
 */
class BaseUri extends \SprayFire\Core\CoreObject implements \SprayFire\Request\Uri {

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
     * @brief The name of the directory the libs, app, and web dir are stored in
     *
     * @property $installDir
     */
    protected $installDir;

    /**
     * @brief Forces the injection of a URI string into the object, this object
     * should forever be associated with the passed URI.
     *
     * @details
     * For convenience the \a $rootDir passed can have trailing and leading slashes,
     * they will be removed.
     *
     * @param $uri string
     * @param $rootDir The name of the directory holding libs, app, web directories
     */
    public function __construct($uri, $rootDir) {
        $this->originalUri = $uri;
        $this->installDir = \trim($rootDir, '/ ');
        $parsedUri = $this->parseUri($this->originalUri);
        $this->setProperties($parsedUri);
    }

    /**
     * @brief Will parse the passed URI and return an associative array with the
     * appropriate fragments stored as 'controller', 'action', 'parameters'.
     *
     * @param $uri String to parse
     */
    protected function parseUri($uri) {
        $decodedUri = \urldecode($uri);
        $uriFragments = $this->trimAndExplodeUri($decodedUri);
        return $this->parseUriFragments($uriFragments);
    }

    /**
     * @brief Will remove leading and forward '/' and the root install directory
     * and return a 0-index array containing the remaining fragments.
     *
     * @return array in controller, action, param1, param2, paramN order, the keys
     *         should be properly numerically index.
     */
    protected function trimAndExplodeUri($uri) {
        $parsedUri = \parse_url($uri);
        $path = $parsedUri['path'];
        $path = \trim($path, '/');
        $explodedPath = \explode('/', $path);
        if ($explodedPath[0] === $this->installDir) {
            unset($explodedPath[0]);
        }
        return \array_values($explodedPath);
    }

    /**
     * @brief Will turn an array of \a $explodedUriFragments into a parsed array
     * indicating the appropriate controller, action and list of parameters.
     *
     * @details
     * If the \a $explodedUriFragments do not have any values for the controller
     * or action the libs.sprayfire.request.Uri constants appropriate for that
     * value will be returned.  The parameter key will always return with some
     * kind of array as its value.
     *
     * @return associative array with the following keys: 'controller', 'action', 'parameters'
     */
    protected function parseUriFragments($uriFragments) {
        $controller = \SprayFire\Request\Uri::DEFAULT_CONTROLLER;
        $action = \SprayFire\Request\Uri::DEFAULT_ACTION;
        $parameters = array();

        if (!empty($uriFragments) && !empty($uriFragments[0])) {
            if (!$this->isParameterString($uriFragments[0])) {
                $controller = \array_shift($uriFragments);
                if (!empty($uriFragments)) {
                    if (!$this->isParameterString($uriFragments[0])) {
                        $action = \array_shift($uriFragments);
                        $parameters = $this->removeParameterMarker($uriFragments);
                    } else {
                        $parameters = $this->removeParameterMarker($uriFragments);
                    }
                }
            } else {
                $parameters = $this->removeParameterMarker($uriFragments);
            }
        }

        return array('controller' => $controller, 'action' => $action, 'parameters' => $parameters);
    }

    /**
     * @brief Determines if the passed \a $value is a parameter string, which we
     * signify as having a colon <code>:</code> in the first character of the string.
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
     *         SprayFire.Request.Uri::DEFAULT_ACTION if none was passed
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @return The controller fragment of the \a $originalUri or
     *         SprayFire.Request.Uri::DEFAULT_CONTROLLER if none was passed
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

    /**
     * @return The name of the root directory set in constructor
     */
    public function getRootDirectory() {
        return $this->installDir;
    }

    /**
     * @param $Object SprayFire.Core.Object to compare the calling object to
     * @return true if the passed \a $Object is equal to the calling object false
     *         if not
     */
    public function equals(\SprayFire\Core\Object $Object) {
        if (!($Object instanceof $this)) {
            return false;
        }

        if ($this->getOriginalUri() === $Object->getOriginalUri()) {
            return true;
        }
        return false;
    }

}