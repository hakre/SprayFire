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

namespace SprayFire\Request\Router;

/**
 * @brief Framework's implementation of a RoutedUri, provides a means to retrieve
 * a controller, action and parameters, the routed URI string and the original
 * URI string that was mapped to the routed.
 *
 * @uses SprayFire.Request.Router.RoutedUri
 * @uses SprayFire.Request.BaseUri
 */
class DispatchUri extends \SprayFire\Request\BaseUri implements \SprayFire\Request\Router\RoutedUri {

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
     * @param $routedUri The routed URI string
     * @param $originalUri The original URI string used to create the mapping
     * @param $installDir The name of the directory storing app and libs folders
     */
    public function __construct($routedUri, $originalUri, $installDir) {
        parent::__construct($originalUri, $installDir);
        $this->routedUri = $routedUri;
        $parsedUri = $this->parseUri($this->routedUri);
        $this->setProperties($parsedUri);
    }

    /**
     * @return The routed URI string passed to the constructor
     */
    public function getRoutedUri() {
        return $this->routedUri;
    }

}