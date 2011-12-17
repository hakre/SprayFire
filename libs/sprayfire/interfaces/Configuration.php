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
 * An interface for configuration objects used by the application and framework.
 *
 * It is expected that the implementation objects will allow read-only properties
 * to hold the various information associated with a configuration.  Each type of
 * configuration object should ultimately read the configuration from a file.  The
 * ability to parse the given file should exist within the class not needing a
 * dependency.  It is expected that any given configuration object will be mutable,
 * allowing the imported configuration values to only be read.
 *
 */
interface Configuration extends Overloadable, \ArrayAccess {

    /**
     * Data can be any type, as long as after the object is constructed it is ready
     * to be read from either as an object or as an array.
     *
     * @param \SplFileInfo $FileInfo
     */
    public function __construct(\SplFileInfo $FileInfo);

}

// End Configuration
