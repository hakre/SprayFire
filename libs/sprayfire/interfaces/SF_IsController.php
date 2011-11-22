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
 * The interface to be implemented by all framework controllers, to include whatever
 * error controller object should be returned if a problem is encountered.
 */
interface SF_IsController {

    /**
     * Ensures that the configuration object is available in case there is anything
     * you need from it.
     */
    public function __construct(SF_IsConfigurationStorage $CoreConfiguration);

    /**
     * The only way to pass data to the view is through an associative array, with
     * the name of the array key being the name of the variable in the view.
     *
     * Return a value to let the calling code know if the data was successfully
     * handed off to the view.
     *
     * @return boolean
     */
    public function giveToView(array $associateVars, $isUnsafeData = true);

    /**
     * Returns an array listing the name of components attached to this controller.
     *
     * If there are no components listed this function should return an empty array.
     *
     * @return array
     */
    public function getComponents();

    /**
     * Returns an array listing the name of models attached to this controller.
     *
     * If there are no models listed this function should return an empty array.
     *
     * @return array
     */
    public function getModels();

    /**
     * Returns an array listing the name of the helpers attached to the view for
     * this controller.
     *
     * If there are no helpers listed this function return an empty array.
     *
     * @return array
     */
    public function getHelpers();

    /**
     * A callback method that will be invoked for every request, as the name implies,
     * before the requested action is invoked.
     *
     * Should return a boolean value of true if the action should be invoked and
     * false if it shouldn't be invoked.
     *
     * @return boolean
     */
    public function beforeAction();

    /**
     * A callback method that will be invoked for every request, that makes it
     * this far without being cancelled by the user, after the requested action
     * is invoked.
     *
     * Should return a boolean valud of true if the view should be rendered and
     * false if it shouldn't.
     *
     * @return boolean
     */
    public function afterAction();

    /**
     * Should return an associative array holding the view data that should be
     * escaped for HTML output.
     *
     * @return array
     */
    public function getUnsafeViewData();

    /**
     * Should return an associative array holding the view data that should NOT
     * be escaped for HTML output.
     *
     * @return array
     */
    public function getSafeViewData();

}

// End SF_IsController
