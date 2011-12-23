<?php

/**
 * @file
 * @brief The framework's implementation of the libs.sprayfire.request.Router
 * interface that allows for a Uri to be parsed and converted into a RoutedUri
 * based on a libs.sprayfire.config.Configuration object passed in the constructor
 * of the router.
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
     * @brief Will take a libs.sprayfire.config.JsonConfig object to determine the
     * mapped URI to use for the request and convert any libs.sprayfire.request.Uri
     * object passed into an appropriate libs.sprayfire.request.RoutedUri object.
     *
     * @details
     * This class **GUARANTEES** that when `SprayFireRouter:;getRoutedUri($Uri)`
     * is invoked an appropriate object will be returned, even if an invalid
     * configuration file is used.
     *
     */
    class SprayFireRouter implements \libs\sprayfire\request\Router {

        /**
         * @brief The libs.sprayfire.config.Configuration object holding the routing
         * keys and the appropriate mapped controller and action.
         *
         * @property $RoutesConfig
         */
        protected $RoutesConfig;

        /**
         * @brief The default controller to use if the \a %default_controller% flag
         * is used in the routes.json configuration file or no controller is specified
         * in a particular mapping.
         *
         * @property $defaultController
         */
        protected $defaultController;

        /**
         * @brief The default action to use if the \a %default_action% flag is used
         * in the routes.json configuration file or no action is specified in a
         * particular mapping.
         *
         * @property $defaultAction
         */
        protected $defaultAction;

        /**
         * @brief Will set the appropriate class properties based on the \a $RoutesConfig
         * object passed.
         *
         * @details
         * It is expected that the \a $RoutesConfig object will have 2 properties
         * set:
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
         * @param $RoutesConfig libs.sprayfire.config.Configuration
         */
        public function __construct(\libs\sprayfire\config\Configuration $RoutesConfig) {
            $this->RoutesConfig = $RoutesConfig;

            if (!isset($RoutesConfig->defaults->controller)) {
                \error_log('The default controller was not properly set, using \'pages\' as default controller.');
                $defaultController = 'pages';
            } else {
                $defaultController = $RoutesConfig->defaults->controller;
            }

            if (!isset($RoutesConfig->defaults->action)) {
                \error_log('The default action was not properly set, using \'index\' as the default action.');
                $defaultAction = 'index';
            } else {
                $defaultAction = $RoutesConfig->defaults->action;
            }

            $this->defaultController = $defaultController;
            $this->defaultAction = $defaultAction;
        }

        /**
         * @brief Will take a libs.sprayfire.request.Uri object and create an
         * appropriate libs.sprayfire.request.RoutedUri object.
         *
         * @details
         * Will produce a <code>SprayFireURIPattern</code> based on the
         * \a $Uri object passed, determine whether the default controller and action
         * or the controller and action passed in \a $Uri should be used, it will
         * then produce the appropriate mapped URI string, create a libs.sprayfire.request.RoutedUri,
         * set the original URI string passed and finally return the RoutedUri
         * object created.
         *
         * @param $Uri libs.sprayfire.request.Uri
         * @return libs.sprayfire.request.RoutedUri This implementation returns
         *         a libs.sprayfire.request.SprayFireUri specifically
         */
        public function getRoutedUri(\libs\sprayfire\request\Uri $Uri) {
            $sprayFireUriPatterns = $this->getSprayFireURIPatterns($Uri);
            $controllerAndAction = $this->getControllerAndActionToUse($Uri);
            $mappedUriString = $this->getMappedUriString($sprayFireUriPatterns, $controllerAndAction);
            $finalUriString = $this->getUriWithParameters($mappedUriString, $Uri->getParameters());
            $RoutedUri = new \libs\sprayfire\request\SprayFireUri($finalUriString);
            $RoutedUri->setOriginalUri($Uri->getOriginalUri());
            return $RoutedUri;
        }

        /**
         * @brief Will return an associative array with 2 keys, 'specific' and
         * 'wild-card', the 'specific' key holds a <code>SprayFireURIPattern</code>
         * holding a specific parameter count whild the 'wild-card' key holds a
         * <code>SprayFireURIPattern</code> holding a wild card parameter count.
         *
         * @param $Uri libs.sprayfire.request\Uri
         * @return array
         */
        private function getSprayFireURIPatterns(\libs\sprayfire\request\Uri $Uri) {
            $defaultControllerPattern = 'DC';
            $defaultActionPattern = 'DA';
            $parameterWildCard = '*';

            $routedController = '';
            $routedAction = '';
            $routedParameters = '';

            $sprayFireURIPattern = '';

            $requestedController = \strtolower($Uri->getController());
            if ($requestedController === \libs\sprayfire\request\Uri::DEFAULT_CONTROLLER) {
                $sprayFireURIPattern .= $defaultControllerPattern . '-';
            } else {
                $sprayFireURIPattern .= $requestedController . '-';
            }

            $requestedAction = \strtolower($Uri->getAction());
            if ($requestedAction === \libs\sprayfire\request\Uri::DEFAULT_ACTION) {
                $sprayFireURIPattern .= $defaultActionPattern . '-';
            } else {
                $sprayFireURIPattern .= $requestedAction .'-';
            }
            $requestedParamCount = \count($Uri->getParameters());
            $specificUri = $sprayFireURIPattern . $requestedParamCount;
            $wildCardUri = $sprayFireURIPattern . $parameterWildCard;

            return array('specific' => $specificUri, 'wild-card' => $wildCardUri);
        }

        /**
         * @brief Will return an associative array with 2 keys, 'controller' and
         * 'action', that will be used to determine the routed URI string to generate.
         *
         * @param $Uri libs.sprayfire.request.Uri
         * @return array
         */
        private function getControllerAndActionToUse(\libs\sprayfire\request\Uri $Uri) {
            $data = array();
            if ($Uri->getController() === \libs\sprayfire\request\Uri::DEFAULT_CONTROLLER) {
                $data['controller'] = $this->defaultController;
            } else {
                $data['controller'] = $Uri->getController();
            }

            if ($Uri->getAction() === \libs\sprayfire\request\Uri::DEFAULT_ACTION) {
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
         * @param $sprayFireUriPatterns array
         * @param $controllerAndAction array
         * @return string Please note that this value does not have any parameters attached to it
         */
        private function getMappedUriString(array $sprayFireUriPatterns, array $controllerAndAction) {
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
         *
         * @param $ConfigFragment libs.sprayfire.datastructs.
         * @param array $controllerAndAction
         * @return array
         */
        private function getMappedController($ConfigFragment, array $controllerAndAction) {
            if (isset($ConfigFragment->controller)) {
                if ($ConfigFragment->controller === \libs\sprayfire\request\Uri::DEFAULT_CONTROLLER) {
                    return $this->defaultController;
                }
                return $ConfigFragment->controller;
            }
            return $controllerAndAction['controller'];
        }

        private function getMappedAction($ConfigFragment, array $controllerAndAction) {
            if (isset($ConfigFragment->action)) {
                if ($ConfigFragment->action === \libs\sprayfire\request\Uri::DEFAULT_ACTION) {
                    return $this->defaultAction;
                }
                return $ConfigFragment->action;
            }
            return $controllerAndAction['action'];
        }

        /**
         * @brief Will take a list of parameter values and convert them into an
         * appropriate URI-like string.
         *
         * @param $mappedUri string
         * @param $parameters array
         * @return string
         */
        private function getUriWithParameters($mappedUri, array $parameters) {
            foreach ($parameters as $param) {
                $mappedUri .= $param . '/';
            }
            return $mappedUri;
        }

    }

    // End SprayFireRouter
}

// End libs.sprayfire