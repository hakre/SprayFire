<?php

/**
 * @file
 * @brief Holds a class that allows for a configuration to be stored by passing
 * a simple array
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
namespace SprayFire\Config;

/**
 * @brief A SprayFire.Config.Configuration object that expects configuration data
 * to be passed in the form of an array.
 *
 * @details
 * Note that there is no implementing code, everything is handled by SprayFire.Core.Structure.ImmutableStoreage
 *
 * @uses SprayFire.Config.Configuration
 * @uses SprayFire.Core.Structure.ImmutableStorage
 */
class ArrayConfig extends \SprayFire\Core\Structure\ImmutableStorage implements \SprayFire\Config\Configuration {

}