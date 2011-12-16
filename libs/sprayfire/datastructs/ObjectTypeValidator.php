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

namespace libs\sprayfire\datastructs;

use \ReflectionClass,
    \ReflectionException,
    \InvalidArgumentException,
    libs\sprayfire\core\CoreObject,
    libs\sprayfire\interfaces\Object;

/**
 * This class ensures that a given object implements or extends a specific interface
 * or class.
 */
class ObjectTypeValidator extends CoreObject {

    /**
     * @var ReflectionClass
     */
    protected $ReflectedParentType;

    /**
     * Will create a ReflectionClass of the passed $parentType, if the
     *
     * @param string $parentType
     * @throws InvalidArgumentException
     */
    public function __construct($parentType) {
        $this->ReflectedParentType = $this->createReflectedParentType($parentType);
    }

    /**
     * @param string $parentType
     * @return ReflectionClass
     * @throws InvalidArgumentException
     */
    private function createReflectedParentType($parentType) {
        $ReflectedParent = null;
        try {
            $ReflectedParent = new \ReflectionClass($parentType);
        } catch (\ReflectionException $ReflectionExc) {
            throw new \InvalidArgumentException('There was an error reflecting the parent type, ' . $parentType, null, $ReflectionExc);
        }
        return $ReflectedParent;
    }

    /**
     * Ensures that the passed $Object either is an instance of, extends  or implements
     * the $ReflectedParentType.
     *
     * We chose to go with Reflection instead of using the is_a() function or
     * instanceof operator due to the fact that the $parentType expected from the
     * user in __construct() is of type string.  By using Reflection we ensure that
     * the class can be included through the ClassLoader and can ensure that
     * interface implementation and class inheritance is covered.
     *
     * @param CoreObject $Object
     * @return boolean
     */
    public function isObjectParentType(Object $Object) {
        $isValid = false;
        $ReflectedParent = $this->ReflectedParentType;
        $parentName = $ReflectedParent->getName();
        try {
            $ReflectedObject = new \ReflectionClass($Object);
            if ($ReflectedParent->isInterface()) {
                if ($ReflectedObject->implementsInterface($parentName)) {
                    $isValid = true;
                }
            } else {
                if ($ReflectedObject->getName() === $parentName || $ReflectedObject->isSubclassOf($parentName)) {
                    $isValid = true;
                }
            }
        } catch (\ReflectionException $ReflectionExc) {
            // @codeCoverageIgnoreStart
            error_log($ReflectionExc->getMessage());
            // @codeCoverageIgnoreEnd
        }
        return $isValid;
    }

    /**
     * @return string
     */
    public function getParentType() {
        return $this->ReflectedParentType->getName();
    }
}

// End ObjectTypeValidator
