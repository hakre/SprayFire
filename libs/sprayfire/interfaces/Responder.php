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
 * The interface implemented by responding objects.
 *
 * Where most frameworks have 'View' objects I don't feel that this properly represents
 * what is really going on.  Often times the 'View' being returned isn't something
 * the user, or anybody else for that matter, actually gets to view.  A lot of the
 * time it is HTML or images but it can be other things going on behind the scenes
 * as well.  Either way, objects implementing SF_IsResponder should be prepared to
 * generate and send the appropriate response to the user, based on the information
 * in the 3 objects passed to the Responder object.
 *
 */
interface Responder {

    /**
     * Assures that the Responder object will have access to data that may be needed
     * to generate the complete response.
     *
     * @param SF_IsConfigurationStorage $CoreConfiguration
     */
    public function __construct(Configuration $CoreConfiguration);

    /**
     * Gathers whatever data is needed from the Controller or Core
     * Configuration objects and generates the response needed to be sent to the
     * user.
     *
     * @param SF_IsController $Controller
     */
    public function generateResponse(Controller $Controller);

    /**
     * Will set whatever HTTP header settings are necessary and send the response
     * created by generateResponse().
     *
     * @internal Do we want to have this return the response, likely as a string,
     * or echo the response out to the user?  Ultimately we need to determine how
     * HTTP header attributes will be set.  More thought needs to be put into this
     * aspect of the framework.
     */
    public function sendResponse();

}

// End Responder
