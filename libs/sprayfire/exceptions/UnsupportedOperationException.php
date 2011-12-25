<?php

/**
 * @file
 * @brief Holds an exception that should be thrown if a method implemented by an
 * interface should not be callable for a specific implementation.
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

namespace libs\sprayfire\exceptions;
use \RuntimeException as RuntimeException;

    /**
     * @brief Thrown when a method implemented from an interface should not be
     * callable by that specific implementation.
     */
    class UnsupportedOperationException extends RuntimeException {

    }

    // End UnsupportedOperationException

// End libs.sprayfire.exceptions