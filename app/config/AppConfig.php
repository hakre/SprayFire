<?php

/**
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
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace app\config;

/**
 * The application's configuration object, mapping the configuration values in the
 * applicaiton XML document to their appropriate property values.
 *
 * The location of the application XML document should be ROOT_PATH/app/config/xml
 * and the name of the actual file is stored in the framework configuration XML
 * document.
 *
 * @uses \SimpleXMLElement
 * @uses \libxml
 * @uses \ReflectionClass
 * @uses \InvalidArgumentException
 * @uses \libs\sprayfire\exceptions\InvalidConfigurationException
 * @uses \libs\sprayfire\exceptions\UnsupportedOperationException
 * @uses \libs\sprayfire\config\XmlConfig
 * @uses \libs\sprayfire\config\BaseConfig
 * @uses \libs\sprayfire\interfaces\Configuration
 * @uses \libs\sprayfire\core\CoreObject
 * @uses \libs\sprayfire\interfaces\Object
 */
class AppConfig extends \libs\sprayfire\config\XmlConfig {

    /**
     * The application version; a string in the following format:
     *
     * MAJOR.MINOR.REVISION-DEPLOYMENT_STAGE
     *
     * @var string
     */
    protected $version;

    /**
     * A string, on or off, that determines whether or not the current application
     * is under development.
     *
     * @var string
     */
    protected $developmentMode;

    /**
     * The name of the default controller if there is none passed in the request
     * URI.
     *
     * If this value is left blank the default controller defined by the FrameworkConfig
     * object will be used in its place.
     *
     * @var string
     */
    protected $routingDefaultController;

    /**
     * The name of the default action if there is none passed in the request URI.
     *
     * If this value is left blank the default action defined by the FrameworkConfig
     * object will be used in its place.
     *
     * @var string
     */
    protected $routingDefaultAction;

    /**
     * The COMPLETE, namespaced name of the class that is responsible for handling
     * errors triggered by PHP.
     *
     * @var string
     */
    protected $errorHandlingClass;

    /**
     * The name of the method associated with the errorHandlingClass that will
     * be used as the actual callback function for the error handler.
     *
     * @var string
     */
    protected $errorHandlingAction;

    /**
     * The COMPLETE, namespaced name of the class that is responsible for handling
     * uncaught exceptions.
     *
     * @var string
     */
    protected $exceptionHandlingClass;

    /**
     * The name of the method associated with the exceptionHandlingClass that is
     * associated with the exceptionHandlingClass and is used as the actual callback
     * for uncaught exceptions.
     *
     * @var string
     */
    protected $exceptionHandlingAction;

    // *************************************************************************
    // Properties below this are ini setting values that should be set in the
    // bootstrapping procedure.
    // *************************************************************************

    /**
     * The default timezone that should be set in the php.ini file; this is set to
     * ensure that there are no erroneous errors thrown.
     *
     * Please see the below link for the appropriate identifier for your timezone.
     * http://www.php.net/manual/en/timezones.php
     *
     * @var string
     */
    protected $timezone;

    /**
     * The charset that should be set in the HTTP header.
     *
     * @var string
     */
    protected $charset;

    /**
     * 1 - Short-open tags are enabled. 0 - Short-open tags are disabled.
     *
     * @var int
     */
    protected $shortOpenTags;

    /**
     * 1 - PHP settings should be exposed. 0 - PHP settings should not be exposed.
     *
     * @var int
     */
    protected $exposePhp;

    /**
     * 1 - ASP tags should be enabled. 0 - ASP tags should be disabled.
     *
     * @var int
     */
    protected $aspTags;

    /**
     * 1 - Display errors. 0 - Do not display errors
     *
     * @var int
     */
    protected $displayErrors;

    /**
     * 1 - Display startup errors. 0 - Do not display startup errors
     *
     * @var int
     */
    protected $displayStartupErrors;

    /**
     * A bitwise string that will be used to set the appropriate error reporting
     * level.
     *
     * @var string
     */
    protected $errorReporting;

    /**
     * Will import an XML document and parse the appropriate values into the associated
     * properties.
     *
     * @param string $filePath
     */
    public function importConfig($filePath) {
        parent::importConfig($filePath);
        $this->parseVersion();
        $this->parseDevelopmentMode();
        $this->parseRoutingDefaults();
        $this->parseErrorHandling();
        $this->parseExceptionHandling();
        $this->parseGlobalIniSettings();
        $this->parseEnvironmentIniSettings();
    }

    /**
     * Will gather the major, minor, revision and deployment stage for the FRAMEWORK,
     * assign the appropriate format to the data and then assign the final value
     * to the 'version' property.
     */
    private function parseVersion() {
        $major = (string) $this->XmlConfiguration->version->major;
        $minor = (string) $this->XmlConfiguration->version->minor;
        $revision = (string) $this->XmlConfiguration->version->revision;
        $deployment = (string) $this->XmlConfiguration->version['deployment-stage'];
        $version = $major . '.' . $minor . '.' . $revision . '-' . $deployment;
        $this->version = $version;
    }

    /**
     * Will determine if the application is currently in development mode or not
     * and assign the appropriate value to the property.
     */
    private function parseDevelopmentMode() {
        $developmentStatus = (string) $this->XmlConfiguration->{'env-configuration'}->{'development-mode'}['value'];
        $this->developmentMode = $developmentStatus;
    }

    /**
     * Gathers the default controller and action that should be used if there is
     * none passed in the REQUEST_URI
     */
    private function parseRoutingDefaults() {
        $routingController = (string) $this->XmlConfiguration->{'routing-default'}->controller;
        $routingAction = (string) $this->XmlConfiguration->{'routing-default'}->action;
        $this->routingDefaultController = $routingController;
        $this->routingDefaultAction = $routingAction;
    }

    /**
     * Gathers the complete namespaced name of the class, and the action associated
     * with that class, responsible for handling errors.
     */
    private function parseErrorHandling() {
        $errorHandlingClass = (string) $this->XmlConfiguration->{'error-handling'}->class;
        $errorHandlingAction = (string) $this->XmlConfiguration->{'error-handling'}->action;
        $this->errorHandlingClass = $errorHandlingClass;
        $this->errorHandlingAction = $errorHandlingAction;
    }

    /**
     * Gathers the complete namespaced name of the class, and the action associated
     * with that class, responsible for handling uncaught exceptions.
     */
    private function parseExceptionHandling() {
        $exceptionHandlingClass = (string) $this->XmlConfiguration->{'exception-handling'}->class;
        $exceptionHandlingAction = (string) $this->XmlConfiguration->{'exception-handling'}->action;
        $this->exceptionHandlingClass = $exceptionHandlingClass;
        $this->exceptionHandlingAction = $exceptionHandlingAction;
    }

    /**
     * Parse the global INI settings that are defined in the configuration file.
     */
    private function parseGlobalIniSettings() {
        $this->parseIniTimezone();
        $this->parseIniCharset();
        $this->parseIniShortOpenTags();
        $this->parseIniExposePhp();
        $this->parseIniAspTags();
    }

    private function parseIniTimezone() {
        $timezone = (string) $this->XmlConfiguration->{'env-configuration'}->{'global-ini-settings'}->timezone;
        $this->timezone = $timezone;
    }

    private function parseIniCharset() {
        $charset = (string) $this->XmlConfiguration->{'env-configuration'}->{'global-ini-settings'}->charset;
        $this->charset = $charset;
    }

    private function parseIniShortOpenTags() {
        $shortOpenTagConfig = (string) $this->XmlConfiguration->{'env-configuration'}->{'global-ini-settings'}->{'short-open-tags'}['value'];
        $shortOpenTagIni = $this->convertConfigBooleanToIniBoolean($shortOpenTagConfig);
        $this->shortOpenTags = $shortOpenTagIni;
    }

    private function parseIniExposePhp() {
        $exposePhpConfig = (string) $this->XmlConfiguration->{'env-configuration'}->{'global-ini-settings'}->{'expose-php'}['value'];
        $exposePhpIni = $this->convertConfigBooleanToIniBoolean($exposePhpConfig);
        $this->exposePhp = $exposePhpIni;
    }

    private function parseIniAspTags() {
        $aspTagsConfig = (string) $this->XmlConfiguration->{'env-configuration'}->{'global-ini-settings'}->{'asp-tags'}['value'];
        $aspTagsIni = $this->convertConfigBooleanToIniBoolean($aspTagsConfig);
        $this->aspTags = $aspTagsIni;
    }

    /**
     * Parse the environment specific configuration settings; the appropriate XML
     * element is retrieved, based on the value of developmentMode.
     */
    private function parseEnvironmentIniSettings() {
        if ($this->developmentMode === 'on') {
            $XmlElement = $this->XmlConfiguration->{'env-configuration'}->{'development-ini-settings'};
        } else {
            $XmlElement = $this->XmlConfiguration->{'env-configuration'}->{'production-ini-settings'};
        }
        $this->setIniValues($XmlElement);
    }

    /**
     * Will take an XML element and retrieve the development-specific ini settings
     * from that element.
     *
     * @param \SimpleXMLElement $XmlElement
     */
    private function setIniValues(\SimpleXMLElement $XmlElement) {
        $displayErrorsConfig = (string) $XmlElement->{'display-errors'}['value'];
        $displayErrorsIni = $this->convertConfigBooleanToIniBoolean($displayErrorsConfig);
        $displayStartupErrorsConfig = (string) $XmlElement->{'display-startup-errors'}['value'];
        $displayStartupErrorsIni = $this->convertConfigBooleanToIniBoolean($displayStartupErrorsConfig);
        $errorReportingSeverity = (string) $XmlElement->{'error-reporting'};
        $this->displayErrors = $displayErrorsIni;
        $this->displayStartupErrors = $displayStartupErrorsIni;
        $this->errorReporting = $errorReportingSeverity;
    }

    /**
     * Converts a string 'yes' or 'no' value to the appropriate int value to be
     * set in the php.ini file.
     *
     * @param string $configBoolean
     * @return int
     */
    private function convertConfigBooleanToIniBoolean($configBoolean) {
        if ($configBoolean === \libs\sprayfire\config\XmlConfig::CONFIG_YES) {
            return \libs\sprayfire\config\XmlConfig::INI_YES;
        }
        return \libs\sprayfire\config\XmlConfig::INI_NO;
    }

}

// End AppConfig