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
 * The framework's default controller object, implements the IsController interface
 * and is also an overloadable object.
 *
 * The controller is supposed to interact with models and ultimately be a primary
 * source of data for the responder.  This controller is capable of storing safe
 * and unsafe data for the responder.  We have this separation of data into safe
 * and unsafe categories so we know what, if any, sanitation needs to be done before
 * the response is sent to the user.
 *
 */
abstract class SF_Controller extends SF_CoreObject implements SF_IsController {

    /**
     * An associative array holding 2 keys, one to represent safe data (no need
     * to have data escaped by view) and the other to represent unsafe data (there
     * is a need to have data escaped by view).
     *
     * Each key will itself hold an associative array, with the key of the array
     * representing the variable name and the value of that key being the data
     * stored by that variable.
     *
     * @var array
     */
    private $responderData = array();

    /**
     * A list of components attached to the controller.
     *
     * @var array
     */
    protected $components = array();

    /**
     * A list of models attached to the controller.
     *
     * @var array
     */
    protected $models = array();

    /**
     * A list of helpers to attach to the view.
     *
     * @var array
     */
    protected $helpers = array();

    /**
     * The name of the layout template to use.
     *
     * @var string
     */
    protected $contentTemplate;

    /**
     * The name of the content template to use.
     *
     * @var string
     */
    protected $layoutTemplate;

    /**
     * The name of the responder object to use for this controller, leave this
     * null to use the default responder as set in the CoreConfiguration object.
     *
     * @var string
     */
    protected $responderName;

    /**
     * The framework core configuration object.
     *
     * @var SF_CoreConfiguration
     */
    protected $CoreConfiguration;

    /**
     * Ensures the data necessary for the class to function is properly initialized.
     *
     * If this method is overridden in child classes be sure to invoke the parent
     * constructor, passing a SF_IsConfigurationStorage object to the constructor.
     * For example, the constructor for child classes may look like:
     *
     *     public function __construct(SF_IsConfigurationStorage $CoreConfiguration) {
     *         parent::__construct($CoreConfiguration);
     *         // do your stuff here!
     *     }
     *
     *
     * @param SF_IsConfigurationStorage $CoreConfiguration
     */
    public function __construct(SF_IsConfigurationStorage $CoreConfiguration) {
        $this->CoreConfiguration = $CoreConfiguration;
        $this->responderData['unsafeData'] = array();
        $this->responderData['safeData'] = array();
    }

    /**
     * Will add an associative array to the list of data to be passed to the responder,
     * selectively choosing whether the data should be considered 'safe', meaning
     * it does not need to be escaped on output, or the data should be considered
     * 'unsafe', meaning it does need to be escaped on output.
     *
     * @param array $variablesForResponder
     * @param boolean $shouldEscapeData
     */
    public function giveToResponder(array $variablesForResponder, $shouldEscapeData = true) {
        foreach ($variablesForResponder as $key => $value) {
            if ($shouldEscapeData) {
                $this->responderData['unsafeData'][$key] = $value;
            } else {
                $this->responderData['safeData'][$key] = $value;
            }
        }
    }

    /**
     * The framework callback that is invoked before the requested action.
     */
    public function beforeAction() {

    }

    /**
     * The framework callback that is invoked after the requested action.
     */
    public function afterAction() {

    }

    /**
     * Returns an array of data that should be considered unsafe and needs to be
     * escaped on output.
     *
     * @return array
     */
    public function getUnsafeData() {
        return $this->responderData['unsafeData'];

    }

    /**
     * Returns an array of data that should be considered safe and does not need
     * to be escaped on output.
     *
     * @return array
     */
    public function getSafeData() {
        return $this->responderData['safeData'];
    }

    /**
     * @return array
     */
    public function getComponents() {
        return $this->components;
    }

    /**
     * @return array
     */
    public function getModels() {
        return $this->models;
    }

    /**
     * @return array
     */
    public function getHelpers() {
        return $this->helpers;
    }

    /**
     * @return mixed
     */
    public function getLayoutTemplate() {
        return $this->layoutTemplate;
    }

    /**
     * @param string $templateName
     * @return boolean
     */
    public function setLayoutTemplate($templateName) {
        $isTemplateSet = $this->setTemplateValue('layoutTemplate', $templateName);
        return $isTemplateSet;
    }

    /**
     * @return mixed
     */
    public function getContentTemplate() {
        return $this->contentTemplate;
    }

    /**
     * @param string $templateName
     * @return boolean
     */
    public function setContentTemplate($templateName) {
        $isTemplateSet = $this->setTemplateValue('contentTemplate', $templateName);
        return $isTemplateSet;
    }

    /**
     * Will set the property passed to the $templateName passed if that value is
     * not an object, array and is a string.
     *
     * @param string $propertyName
     * @param string $templateName
     * @return boolean
     */
    private function setTemplateValue($propertyName, $templateName) {
        if (is_object($templateName) || is_array($templateName) || !is_string($templateName)) {
            error_log('Attempting to set the ' . $propertyName . ' to a non-string value.', E_USER_NOTICE);
            return false;
        }
        $this->$propertyname = $templateName;
        return true;
    }

    /**
     * @return mixed
     */
    public function getResponder() {
        return $this->responderName;
    }

}

// End SF_Controller
