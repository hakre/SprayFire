<?php

/**
 * @file
 * @brief The framework's implementation of the SprayFire.Request.Router interface
 * that allows for a Uri to be parsed and converted into a RoutedUri based on a
 * SprayFire.Config.Configuration object injected at construction.
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
 * @brief Will take a SprayFire.Config.JsonConfig object to determine the
 * mapped URI to use for the request and convert any SprayFire.Request.Uri
 * object passed into an appropriate SprayFire.Request.RoutedUri object.
 *
 * @details
 * This class **GUARANTEES** that when <code>SprayFireRouter::getRoutedUri($Uri)</code>
 * is invoked an appropriate SprayFire.Request.RoutedUri object will be returned,
 * even if an invalid configuration file is used.
 */
class SprayFireRouter extends \SprayFire\Logger\CoreObject implements \SprayFire\Request\Router {

    /**
     * @brief The SprayFire.Config.Configuration object holding the routing keys
     * and the appropriate mapped controller and action.
     *
     * @property $RoutesConfig
     */
    protected $RoutesConfig;

    /**
     * @brief The default controller to use if the %default_controller% flag is
     * set in the routes.json configuration file or no controller is specified in
     * a particular mapping.
     *
     * @property $defaultController
     */
    protected $defaultController;

    /**
     * @brief The default action to use if the %default_action% flag is set in the
     * routes.json configuration file or no action is specified in a particular
     * mapping.
     *
     * @property $defaultAction
     */
    protected $defaultAction;

    /**
     * @brief Will set the appropriate class properties based on the \a $RoutesConfig
     * object passed.
     *
     * @details
     * It is expected that the \a $RoutesConfig object will have, at minimum, 2
     * properties set:
     *
     * <code>$RoutesConfig->defaults->controller</code>
     *
     * and
     *
     * <code>$RoutesConfig->defaults->action</code>
     *
     * Obviously, these values will be used for the \a $defaultController and
     * \a $defaultAction attributes if they appear.  If they are not present
     * in the \a $RoutesConfig object an error will be logged and a framework
     * chosen controller and action will be invoked.
     *
     * @param $RoutesConfig SprayFire.Config.Configuration
     * @param $Log SprayFire.Logger.Log
     */
    public function __construct(\SprayFire\Config\Configuration $RoutesConfig, \SprayFire\Logger\Log $Log) {
        parent::__construct($Log);
        $this->RoutesConfig = $RoutesConfig;
        if (!isset($RoutesConfig->defaults->controller)) {
            $this->log('The default controller was not properly set, using \'pages\' as default controller.');
            $defaultController = 'pages';
        } else {
            $defaultController = $RoutesConfig->defaults->controller;
        }

        if (!isset($RoutesConfig->defaults->action)) {
            $this->log('The default action was not properly set, using \'index\' as the default action.');
            $defaultAction = 'index';
        } else {
            $defaultAction = $RoutesConfig->defaults->action;
        }

        $this->defaultController = $defaultController;
        $this->defaultAction = $defaultAction;
    }

    /**
     * @brief Will take a SprayFire.Request.Uri object and create an appropriate
     * SprayFire.Request.RoutedUri object.
     *
     * @details
     * This function will:
     * - Gather 2 SprayFireURI Patterns, a specific parameter count pattern and
     * a wild-card count pattern.
     * - Gather the controller and action to use for routing purposes, will be either
     * the default controller set in the configuration or the controller passed in
     * the URI
     * - Check the specific SprayFireURI Pattern for a routing, if the specific
     * pattern does not exist checks the wild-card SprayFireURI pattern.
     * - Creates the mapped URI based on the controller and action set in the routing
     * or based on the default/requested controller and action
     * - Returns a properly instantiated SprayFire.Request.DispatchUri object
     *
     * @param $Uri SprayFire.Request.Uri
     * @return SprayFire.Request.RoutedUri This implementation returns
     *         a SprayFire.Request.DispatchUri specifically
     */
    public function getRoutedUri(\SprayFire\Request\Uri $Uri) {
        $sprayFireUriPatterns = $this->getSprayFireURIPatterns($Uri);
        $controllerAndAction = $this->getControllerAndActionToUse($Uri);
        $mappedUriString = $this->getMappedUriString($sprayFireUriPatterns, $controllerAndAction);
        $finalUriString = $this->getUriWithParameters($mappedUriString, $Uri->getParameters());
        $RoutedUri = new \SprayFire\Request\DispatchUri($finalUriString, $Uri->getOriginalUri(), $Uri->getRootDirectory());
        return $RoutedUri;
    }

    /**
     * @brief Will return an associative array with 2 keys, 'specific' and
     * 'wild-card', the 'specific' key holds a <code>SprayFireURIPattern</code>
     * holding a specific parameter count while the 'wild-card' key holds a
     * <code>SprayFireURIPattern</code> holding a wild card parameter count.
     *
     * @param $Uri SprayFire.Request.Uri to get a SprayFireURI Pattern
     * @return Associative array holding 'specific' and 'wild-card' SprayFireURI Patterns
     */
    protected function getSprayFireURIPatterns(\SprayFire\Request\Uri $Uri) {
        $defaultControllerPattern = 'DC';
        $defaultActionPattern = 'DA';
        $parameterWildCard = '*';

        $sprayFireURIPattern = '';

        $requestedController = \strtolower($Uri->getController());
        if ($requestedController === \SprayFire\Request\Uri::DEFAULT_CONTROLLER) {
            $sprayFireURIPattern .= $defaultControllerPattern . '-';
        } else {
            $sprayFireURIPattern .= $requestedController . '-';
        }

        $requestedAction = \strtolower($Uri->getAction());
        if ($requestedAction === \SprayFire\Request\Uri::DEFAULT_ACTION) {
            $sprayFireURIPattern .= $defaultActionPattern . '-';
        } else {
            $sprayFireURIPattern .= $requestedAction .'-';
        }
        $requestedParamCount = \count($Uri->getParameters());
        $specificPattern = $sprayFireURIPattern . $requestedParamCount;
        $wildCardPattern = $sprayFireURIPattern . $parameterWildCard;

        return array('specific' => $specificPattern, 'wild-card' => $wildCardPattern);
    }

    /**
     * @param $Uri SprayFire.Request.Uri
     * @return return an associative array with 2 keys, \a controller and
     *         \a action, that will be used to determine the routed URI string
     *         to generate.
     */
    protected function getControllerAndActionToUse(Uri $Uri) {
        $data = array();
        if ($Uri->getController() === \SprayFire\Request\Uri::DEFAULT_CONTROLLER) {
            $data['controller'] = $this->defaultController;
        } else {
            $data['controller'] = $Uri->getController();
        }

        if ($Uri->getAction() === \SprayFire\Request\Uri::DEFAULT_ACTION) {
            $data['action'] = $this->defaultAction;
        } else {
            $data['action'] = $Uri->getAction();
        }
        return $data;
    }

    /**
     * @brief Will return a URI-like string based on the mapping found in the
     * \a $RoutesConfig, the \a $sprayFireUriPatterns to search for and the
     * \a $controllerAndAction that was requested.
     *
     * @param $sprayFireUriPatterns  associative array from getSprayFireURIPatterns()
     * @param $controllerAndAction associative array from getControllerAndActionToUse()
     * @return string Please note that this value does not have any parameters attached to it
     */
    protected function getMappedUriString(array $sprayFireUriPatterns, array $controllerAndAction) {
        $specific = $sprayFireUriPatterns['specific'];
        $wildCard = $sprayFireUriPatterns['wild-card'];
        $SpecificRoute = $this->RoutesConfig->routes->$specific;
        $WildCardRoute = $this->RoutesConfig->routes->$wildCard;
        $mappedController = '';
        $mappedAction = '';

        if (isset($SpecificRoute)) {
            $mappedController = $this->getMappedController($SpecificRoute, $controllerAndAction);
            $mappedAction = $this->getMappedAction($SpecificRoute, $controllerAndAction);
        } else {
            if (isset($WildCardRoute)) {
                $mappedController = $this->getMappedController($WildCardRoute, $controllerAndAction);
                $mappedAction = $this->getMappedAction($WildCardRoute, $controllerAndAction);
            } else {
                $mappedController = $controllerAndAction['controller'];
                $mappedAction = $controllerAndAction['action'];
            }
        }
        return '/' . $mappedController . '/' . $mappedAction . '/';
    }

    /**
     * @param $ConfigFragment SprayFire.Datastructs.ImmutableStorage from \a $RoutesConfig
     * @param $controllerAndAction associative array returned from getControllerAndActionToUse()
     * @return string value representing controller fragment
     */
    protected function getMappedController($ConfigFragment, array $controllerAndAction) {
        if (isset($ConfigFragment->controller)) {
            if ($ConfigFragment->controller === \SprayFire\Request\Uri::DEFAULT_CONTROLLER) {
                return $this->defaultController;
            }
            return $ConfigFragment->controller;
        }
        return $controllerAndAction['controller'];
    }

    /**
     * @param $ConfigFragment SprayFire.Datastructs.ImmutableStorage from \a $RoutesConfig
     * @param $controllerAndAction associative array returned from getControllerAndActionToUse()
     * @return string value representing action fragment
     */
    protected function getMappedAction($ConfigFragment, array $controllerAndAction) {
        if (isset($ConfigFragment->action)) {
            if ($ConfigFragment->action === \SprayFire\Request\Uri::DEFAULT_ACTION) {
                return $this->defaultAction;
            }
            return $ConfigFragment->action;
        }
        return $controllerAndAction['action'];
    }

    /**
     * @param $mappedUri string
     * @param $parameters array
     * @return A string with \a $parameters appended to \a $mappedUri, separated by '/'
     */
    protected function getUriWithParameters($mappedUri, array $parameters) {
        foreach ($parameters as $param) {
            $mappedUri .= $param . '/';
        }
        return $mappedUri;
    }

}