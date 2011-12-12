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

namespace libs\sprayfire\config;

/**
 * The framework configuration object, responsible for mapping the XML configuration
 * values to the properties of this object.
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
final class FrameworkConfig extends XmlConfig {

    /**
     * The value for true for ini settings that expect a boolean value
     *
     * @var int
     */
    const INI_YES = 1;

    /**
     * The value for false for ini settings that expect a boolean value
     *
     * @var int
     */
    const INI_NO = 0;

    /**
     * The value for true in the configuration files.
     *
     * @var string
     */
    const CONFIG_YES = 'yes';

    /**
     * The value for false in the configuration files.
     *
     * @var string
     */
    const CONFIG_NO = 'no';

    /**
     * The framework version; a string in the following format:
     *
     * MAJOR.MINOR.REVISION-DEPLOYMENT_STAGE
     *
     * @var string
     */
    protected $version;

    /**
     * The filename, and extension, holding the app's configuration file.
     *
     * @var string
     */
    protected $appConfigFile;

    /**
     * The filename, and extension, holding the routes for this application.
     *
     * @var string
     */
    protected $routesConfigFile;

    /**
     * The default controller if there is none specified in the URI.
     *
     * Note, this value is only used if there is none defined in the application
     * configuration.
     *
     * @var string
     */
    protected $routesDefaultController;

    /**
     * The default action to be invoked by the controller if there is none specified
     * in the URI.
     *
     * Note, this value is only used if there is none defined in the application
     * configuration.
     *
     * @var string
     */
    protected $routesDefaultAction;

    /**
     * The complete, namespaced name of the class to be used for handling errors
     * that may be triggered.
     *
     * Note, triggered errors will either be triggered by your own application or
     * by PHP.  At no point will SprayFire invoke the `trigger_error` function.
     *
     * Also, please notice that you don't need to escape the forward slashes associated
     * with the namespace.  You can simply input the namespace as you normally
     * would in actual source code.
     *
     * @var string
     */
    protected $errorHandlingClass;

    /**
     * The name of the method associated with the errorHandlingClass that will
     * handle error callbacks; this method should be public.
     *
     * @var string
     */
    protected $errorHandlingAction;

    /**
     * The complete, namespaced name of the class to be used for handling uncaught exceptions
     * that may be triggered.
     *
     * The same 'rules' for the errorHandlingClass applies for this property as
     * well.
     *
     * @var string
     */
    protected $exceptionHandlingClass;

    /**
     * The name of the action associated with the exceptionHandlingClass that will
     * handle the callback for uncaught exceptions; this method should be public.
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
     * Will take a configuration file and parse the appropriate configuration values
     * from it, assigning them to the appropriate object properties.
     *
     * @param string $filePath
     */
    public function importConfig($filePath) {
        parent::importConfig($filePath);
        $this->parseVersion();
        $this->parseAppConfigFile();
        $this->parseRouting();
        $this->parseErrorHandling();
        $this->parseExceptionHandling();
        $this->parseGlobalIniSettings();
    }

    /**
     * Will gather the major, minor, revision and deployment stage for the FRAMEWORK,
     * assign the appropriate format to the data and then assign the final value
     * to the 'version' property.
     *
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
     * Will gather the name and extension of the app configuration file and assign
     * it to the 'appConfigFile' property
     */
    private function parseAppConfigFile() {
        $appConfigFile = (string) $this->XmlConfiguration->configuration->{'app-config-file'};
        $this->appConfigFile = $appConfigFile;
    }

    /**
     * Will gather the name and extension of the routes configuration file, the
     * default controller and default action and assign them to their respective
     * properties.
     */
    private function parseRouting() {
        $routesFile = (string) $this->XmlConfiguration->configuration->routing->{'routes-config-file'};
        $defaultController = (string) $this->XmlConfiguration->configuration->routing->{'default-controller'};
        $defaultAction = (string) $this->XmlConfiguration->configuration->routing->{'default-action'};
        $this->routesConfigFile = $routesFile;
        $this->routesDefaultController = $defaultController;
        $this->routesDefaultAction = $defaultAction;
    }

    /**
     * Will gather the error handling class and action and assign the values to
     * the appropriate properties.
     */
    private function parseErrorHandling() {
        $errorHandlingClass = (string) $this->XmlConfiguration->configuration->{'error-handling'}->class;
        $errorHandlingClass = str_replace('\\', '\\\\', $errorHandlingClass);
        $errorHandlingAction = (string) $this->XmlConfiguration->configuration->{'error-handling'}->action;
        $this->errorHandlingClass = $errorHandlingClass;
        $this->errorHandlingAction = $errorHandlingAction;
    }

    /**
     * Will gather the exception handling class and action and assign the values
     * to the appropriate properties.
     */
    private function parseExceptionHandling() {
        $exceptionHandlingClass = (string) $this->XmlConfiguration->configuration->{'exception-handling'}->class;
        $exceptionHandlingClass = str_replace('\\', '\\\\', $exceptionHandlingClass);
        $exceptionHandlingAction = (string) $this->XmlConfiguration->configuration->{'exception-handling'}->action;
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
        $timezone = (string) $this->XmlConfiguration->configuration->{'global-ini-settings'}->timezone;
        $this->timezone = $timezone;
    }

    private function parseIniCharset() {
        $charset = (string) $this->XmlConfiguration->configuration->{'global-ini-settings'}->charset;
        $this->charset = $charset;
    }

    private function parseIniShortOpenTags() {
        $shortOpenTagConfig = (string) $this->XmlConfiguration->configuration->{'global-ini-settings'}->{'short-open-tags'}['value'];
        $shortOpenTagIni = $this->convertConfigBooleanToIniBoolean($shortOpenTagConfig);
        $this->shortOpenTags = $shortOpenTagIni;
    }

    private function parseIniExposePhp() {
        $exposePhpConfig = (string) $this->XmlConfiguration->configuration->{'global-ini-settings'}->{'expose-php'}['value'];
        $exposePhpIni = $this->convertConfigBooleanToIniBoolean($exposePhpConfig);
        $this->exposePhp = $exposePhpIni;
    }

    private function parseIniAspTags() {
        $aspTagsConfig = (string) $this->XmlConfiguration->configuration->{'global-ini-settings'}->{'asp-tags'}['value'];
        $aspTagsIni = $this->convertConfigBooleanToIniBoolean($aspTagsConfig);
        $this->aspTags = $aspTagsIni;
    }

    /**
     * Converts a string 'yes' or 'no' value to the appropriate int value to be
     * set in the php.ini file.
     *
     * @param string $configBoolean
     * @return int
     */
    private function convertConfigBooleanToIniBoolean($configBoolean) {
        if ($configBoolean === self::CONFIG_YES) {
            return self::INI_YES;
        }
        return self::INI_NO;
    }

}

// End FrameworkConfig
