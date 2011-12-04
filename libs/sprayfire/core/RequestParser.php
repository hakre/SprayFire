<?php

/**
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.2.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

/**
 * The RequestParser object will take a URI and convert the string passed to the
 * appropriate parts of a request.
 */
class RequestParser extends CoreObject {

    /**
     * The URI that was used to request the page to be created.
     *
     * @var string
     */
    private $serverUri;

    /**
     * The unaltered name of the controller requested or NULL if there was no
     * controller found.
     *
     * @var mixed
     */
    private $controllerRequested;

    /**
     * The unaltered name of the controller method requested or NULL if there was
     * no action found.
     *
     * @var mixed
     */
    private $actionRequested;

    /**
     * The parameters that should be passed to the controller method invoked.
     *
     * @var array
     */
    private $parametersSent;

    /**
     * Will fix the URI so that it can properly be parsed and then assign the parsed
     * URI to the appropriate properties of the class.
     *
     * @param string $serverUri
     */
    public function __construct($serverUri) {
        $this->serverUri = $serverUri;
        $filteredUri = filter_var($serverUri, FILTER_SANITIZE_URL);
        $decodedUri = urldecode($filteredUri);
        $fixedUri = $this->fixUpServerUri($decodedUri);
        $parsedUri = $this->parseUri($fixedUri);
        $this->assignUriComponents($parsedUri);
    }

    /**
     * Will assure that the URI is ready to be parsed for the requested components.
     *
     * @param string $serverUri
     * @return string
     */
    private function fixUpServerUri($serverUri) {
        $fixedUri = $this->removeFrameworkDirectory($serverUri);
        $fixedUri = $this->trimUpUri($fixedUri);
        return $fixedUri;
    }

    /**
     * Will remove the framework directory, and any slashes trailing the framework
     * directory, from a URI.
     *
     * @param string $serverUri
     * @return string
     */
    private function removeFrameworkDirectory($serverUri) {
        $frameworkDir = basename(FRAMEWORK_PATH);
        $pattern = '/^(\/' . $frameworkDir . ')/';
        $replaceFrameworkDirectory = preg_replace($pattern, '', $serverUri);
        return $replaceFrameworkDirectory;
    }

    /**
     * Will remove any trailing and leading slashes and spaces.
     *
     * @param string $fixedUri
     * @return string
     */
    private function trimUpUri($fixedUri) {
        $trimmedUri = ltrim($fixedUri, '/');
        $trimmedUri = rtrim($trimmedUri, '/');
        return $trimmedUri;
    }

    /**
     * Will parse the passed URI into three different components, the controller,
     * action and parameters.
     *
     * @param string $serverUri
     * @return array
     */
    private function parseUri($serverUri) {
        $explodedUriParts = explode('/', $serverUri);
        $data = array();
        $data['controller'] = $this->parseNextElement($explodedUriParts);
        $data['action'] = $this->parseNextElement($explodedUriParts);
        $data['parameters'] = $explodedUriParts;
        return $data;
    }

    /**
     * Removes an element from the beginning of the passed array and returns that
     * element as a string value.
     *
     * @param array $explodedUriParts
     * @return string
     */
    private function parseNextElement(array &$explodedUriParts) {
        $element = array_shift($explodedUriParts);
        return $element;
    }

    /**
     * Takes an associative array with the proper keys and then assigns the values
     * associated with those keys to the respect property.
     *
     * @param array $parsedUri
     */
    private function assignUriComponents(array $parsedUri) {
        $this->controllerRequested = $parsedUri['controller'];
        $this->actionRequested = $parsedUri['action'];
        $this->parametersSent = $parsedUri['parameters'];
    }

    /**
     * Returns the original, unaltered request URI passed in the constructor.
     *
     * @return string
     */
    public function getServerUri() {
        return $this->serverUri;
    }

    /**
     * Returns the first element of the parsed URI, which should correlate to the
     * name of the controller to instantiate or null if no controller was requested.
     *
     * @return string|null
     */
    public function getRequestedController() {
        return $this->controllerRequested;
    }

    /**
     * Returns the second element of the parsed URI, which should correlate to the
     * name of the action to invoke or null if no action was requested.
     *
     * @return string|null
     */
    public function getRequestedAction() {
        return $this->actionRequested;
    }

    /**
     * Returns the parameters for the requested action, which would be a list of
     * 3, 4, 5, ..., n size, depending on the parameters passed in the URI.
     *
     * If no parameters were passed the array returned will be empty.
     *
     * @return array
     */
    public function getSentParameters() {
        return $this->parametersSent;
    }

}

// End RequestParser
