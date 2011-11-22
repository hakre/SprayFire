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
 * The interface implemented by the framework objects that render the view for
 * the request.
 */
interface SF_IsViewRenderer {

    /**
     * Ensures that the view object has the objects it needs to properly render
     * the view for the given request.
     *
     * @param SF_IsController $Controller
     * @param SF_IsConfigurationStorage $CoreConfiguration
     */
    public function __construct(SF_IsController $Controller);

    /**
     * Will render the appropriate HTML markup according to the values passed by
     * the controller.
     *
     * @return string
     */
    public function renderView();

    /**
     * Will render a specific file, as indicated by the name of the element, into
     * a layout or content template.
     *
     * The second paramter is an associative array containing $key/$value pairs that
     * should be converted into $variables to be used by the element.
     *
     * @param string $elementName
     * @param array $elementVariables
     * @return string
     */
    public function renderElement($elementName, array $elementVariables = array());

    /**
     * Assigns a template file to the layout that should be used.
     *
     * @param string $templateName
     */
    public function setLayoutTemplate($templateName);

    /**
     *
     * @param string $templateName
     */
    public function setContentTemplate($templateName);

}

// End SF_IsViewRenderer
