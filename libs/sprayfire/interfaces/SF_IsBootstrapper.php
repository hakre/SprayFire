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
 * An interface implemented by all framework and app bootstrapping objects.
 */
interface SF_IsBootstrapper {

    /**
     * Every bootstrapper should be prepared to accept a configuration object, as
     * many of the responsibilities for the bootstrapping object is to ensure
     * configuration values are properly set.
     */
    public function __construct(SF_IsConfigurationStorage $configurationObject);

    /**
     * A method that should do whatever bootstrapping features are needed for that
     * particular bootstrap.
     */
    public function runBootstrap();

}

// End SF_IsBootstrapper
