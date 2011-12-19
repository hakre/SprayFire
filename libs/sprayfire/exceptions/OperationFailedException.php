<?php

/**
 * @file
 * @brief Holds an exception thrown if a vital operation could not be executed
 * properly.
 *
 * @details
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
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 */

/**
 * @namespace libs.sprayfire.exceptions
 * @brief Holds the various exceptions that may be thrown by the framework, in
 * addition to PHPs pre-defined exceptions.
 */
namespace libs\sprayfire\exceptions {

    /**
     * @brief Thrown if an operation could not be completed successfully and the
     * function's successfullness is critical to the application.
     */
    class OperationFailedException extends \Exception {

    }

    // End OperationFailedException
}

// End libs.sprayfire.exceptions