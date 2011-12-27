<?php

/**
 * @file
 * @brief The interface necessary to turn a URI string into the appropriate fragments needed
 * to process the request.
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

namespace libs\sprayfire\request;

/**
 * @brief Implementations should convert a string into appropriate fragments
 * for the requested contoller, action and parameters.
 *
 * @details
 * The implementation of this interface should take into account that the Uri
 * passed may contain a leading `/` and the name of the installing directory
 * if the framework is installed in a subdirectory of the server root.
 *
 * The following pattern describes the general format URIs should be interpreted:
 *
 * <code>[install_dir]/controller/action/param1/param2/paramN</code>
 *
 * If no value is returned from these then the default values should be returned.
 * For this interface the default values are:
 *
 * Default controller = libs.sprayfire.request.Uri::DEFAULT_CONTROLLER
 * Default action = libs.sprayfire.request.Uri::DEFAULT_ACTION
 * Default parameters = array()
 */
interface Uri {

    /**
     * Returned if the controller fragment of the URI is not set.
     *
     * @property DEFAULT_CONTROLLER
     */
    const DEFAULT_CONTROLLER = '%default_controller%';

    /**
     * Returned if the default action should be used.
     *
     * @property DEFAULT_ACTION
     */
    const DEFAULT_ACTION = '%default_action%';

    /**
     * @return string The original unaltered URI
     */
    public function getOriginalUri();

    /**
     * @return string The URI controller fragment or DEFAULT_CONTROLLER if no controller was given
     */
    public function getController();

    /**
     * @return string The URI action fragment or DEFAULT_ACTION if no action was given
     */
    public function getAction();

    /**
     * @return array An array of parameter data fragments or an empty array if none were given
     */
    public function getParameters();

}