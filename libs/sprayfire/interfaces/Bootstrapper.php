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
 * @version 0.10b
 * @since 0.10b
 */

namespace libs\sprayfire\interfaces;

/**
 * An interface implemented by all framework and app bootstrapping objects.
 */
interface Bootstrapper {

    /**
     * Every bootstrapper should be prepared to accept a configuration object, as
     * many of the responsibilities for the bootstrapping object is to ensure
     * configuration values are properly set.
     *
     * @param Configuration $ConfigurationObject
     */
    public function __construct(Configuration $ConfigurationObject);

    /**
     * A method that should do whatever bootstrapping features are needed for that
     * particular bootstrap.
     */
    public function runBootstrap();

}

// End Bootstrapper
