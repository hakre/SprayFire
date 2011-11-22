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
    private $viewData = array();

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
        $this->viewData['unsafeData'] = array();
        $this->viewData['safeData'] = array();
    }

    /**
     * Will add an associative array to the list of data to be passed to the view,
     * selectively choosing whether the data should be considered 'safe', meaning
     * it does not need to be escaped on output, or the data should be considered
     * 'unsafe', meaning it does need to be escaped on output.
     *
     * @param array $variablesForView
     * @param boolean $shouldEscapeData
     */
    public function giveToView(array $variablesForView, $shouldEscapeData = true) {
        foreach ($variablesForView as $key => $value) {
            if ($shouldEscapeData) {
                $this->viewData['unsafeData'][$key] = $value;
            } else {
                $this->viewData['safeData'][$key] = $value;
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
    public function getUnsafeViewData() {
        return $this->viewData['unsafeData'];

    }

    /**
     * Returns an array of data that should be considered safe and does not need
     * to be escaped on output.
     *
     * @return array
     */
    public function getSafeViewData() {
        return $this->viewData['safeData'];
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
     * @return string
     */
    public function getLayoutTemplate() {
        return $this->layoutTemplate;
    }

    /**
     * @return string
     */
    public function getContentTemplate() {
        return $this->contentTemplate;
    }

}

// End SF_Controller
