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
 * This class is an implementation of the DataList interface, with data storage and
 * iteration responsibilities being handled by its immediate superclass, IteratorList.
 *
 * This object guarantees that the objects added to it implement the appropriate
 * interface or extends the appropriate class.  This data structure will also ensure
 * that only unique objects are added to the List.  The only means in which to set the
 * type of objects stored in the class is through the constructor.  If you want
 * the list to store different types of objects you will need to create a new one.
 */
class UniqueList extends IteratorList implements DataList {

    /**
     * A zero in the $maxSize property value means the list may have an unlimited
     * number of objects.
     *
     * @var int
     */
    private $maxSize;

    /**
     * The interface or class name all objects in this list must implement or
     * extend.
     *
     * @var ReflectionClass
     */
    private $ReflectedParentType;

    /**
     * Ensures the name of the interface or class the objects in the list should
     * implement or extend.
     *
     * @param string $interfaceOrClassName
     * @param int $maxSize
     * @throws IllegalArgumentException
     */
    public function __construct($parentType) {
        $this->setParentType($parentType);
    }

    /**
     * Creates a ReflectionClass object for the given parent type and sets the
     * ReflectedParentType property to said ReflectionClass.
     *
     * @param string $parentType
     * @throws IllegalArgumentException
     */
    private function setParentType($parentType) {
        $ReflectedType = $this->createReflectedParentType($parentType);
        $this->ReflectedParentType = $ReflectedType;
    }

    /**
     * @param string $parentType
     * @return ReflectionClass
     */
    private function createReflectedParentType($parentType) {
        $ReflectedParent = null;
        try {
            $ReflectedParent = new ReflectionClass($parentType);
        } catch (ReflectionException $ReflectionExc) {
            throw new IllegalArgumentException('There was an error reflecting the parent type, ' . $parentType, null, $ReflectionExc);
        }
        return $ReflectedParent;
    }

    /**
     * Will add the passed object to the list, given (a) there are enough empty buckets
     * in the list, (b) the object properly implements the $parentType and (c) the
     * object does not already exist in the list.
     *
     * @param CoreObject $Object
     * @throws IllegalArgumentException
     *         OutOfRangeException
     */
    public function add(CoreObject $Object) {
        $this->throwExceptionIfNoAvailableBuckets();
        $this->throwExceptionIfObjectNotParentType ($Object);
        $isObjectInList = $this->contains($Object);
        if (!$isObjectInList) {
            $this->_add($Object);
        }
    }

    /**
     * Should change the index of the given list to the object passed.
     *
     * @param int $index
     * @param CoreObject $Object
     * @throws OutOfRangeException
     *         IllegalArgumentException
     */
    public function set($index, CoreObject $Object) {
        $this->throwExceptionIfIndexOutOfRange($index);
        $this->throwExceptionIfObjectNotParentType($Object);
        $this->dataStorage[$index] = null;
        $this->dataStorage[$index] = $Object;
    }

    /**
     * @throws OutOfRangeException
     */
    private function throwExceptionIfNoAvailableBuckets() {
        $isThereBucketsInList = $this->hasAvailableBuckets();
        if (!$isThereBucketsInList) {
            throw new OutOfRangeException('The list does not have any buckets left to store the object.');
        }
    }

    /**
     * @return boolean
     */
    private function hasAvailableBuckets() {
        $hasBuckets = true;
        if ($this->maxSize > 0) {
            if ($this->size >= $this->maxSize) {
                $hasBuckets = false;
            }
        }
        return $hasBuckets;
    }

    /**
     * @param CoreObject $Object
     * @throws IllegalArgumentException
     *         UnexpectedValueException
     */
    private function throwExceptionIfObjectNotParentType(CoreObject $Object) {
        $isObjectValid = $this->isObjectParentType($Object);
        if (!$isObjectValid) {
            throw new IllegalArgumentException('The object passed does not implement/extend ' . $this->ReflectedParentType->getShortName());
        }
    }

    /**
     * Ensures that the passed $Object either is an instance of, extends  or implements
     * the $ReflectedParentType.
     *
     * @param CoreObject $Object
     * @return boolean
     */
    private function isObjectParentType(CoreObject $Object) {
        $isValid = false;
        $ReflectedParent = $this->ReflectedParentType;
        $parentName = $ReflectedParent->getShortName();
        try {
            $ReflectedObject = new ReflectionClass($Object);
            if ($ReflectedParent->isInterface()) {
                if ($ReflectedObject->implementsInterface($parentName)) {
                    $isValid = true;
                }
            } else {
                if ($ReflectedObject->getShortName() === $parentName || $ReflectedObject->isSubclassOf($parentName)) {
                    $isValid = true;
                }
            }
        } catch (ReflectionException $ReflectionExc) {
            // @codeCoverageIgnoreStart
            trigger_error($ReflectionExc->getMessage(), E_USER_WARNING);
            // @codeCoverageIgnoreEnd
        }
        return $isValid;
    }

    /**
     * Will add the passed object to the storage and increment the size of the
     * list.
     *
     * @param CoreObject $Object
     */
    private function _add(CoreObject $Object) {
        $arrayIndex = $this->size;
        $this->dataStorage[$arrayIndex] = $Object;
        $this->size++;
    }

    /**
     * Will ensure the index passed is a valid range and return the CoreObject
     * associated with it if it is a valid range.
     *
     * @param int $index
     * @return CoreObject
     * @throws OutOfRangeException
     */
    public function get($index) {
        $this->throwExceptionIfIndexOutOfRange($index);
        $Object = $this->dataStorage[$index];
        return $Object;
    }

    /**
     * @param int $index
     * @throws OutOfRangeException
     */
    private function throwExceptionIfIndexOutOfRange($index) {
        if (!is_int($index) || $index < 0 || $index >= $this->size()) {
            throw new OutOfRangeException('The requested index is not valid.');
        }
    }

    /**
     * Determines whether or not the list contains the given object.
     *
     * @param CoreObject $Object
     * @return boolean
     */
    public function contains(CoreObject $Object) {
        $objectContained = false;
        $objectIndex = $this->indexOf($Object);
        if ($objectIndex >= 0) {
            $objectContained = true;
        }
        return $objectContained;
    }

    /**
     * Returns the number representing the index for the passed element, or -1 if
     * the element could not be found.
     *
     * @param CoreObject $Object
     * @return int
     */
    public function indexOf(CoreObject $Object) {
        $index = -1;
        for ($i = 0; $i < $this->size(); $i++) {
            $ListedObject = $this->dataStorage[$i];
            if ($Object->equals($ListedObject)) {
                $index = $i;
            }
        }
        return $index;
    }

    /**
     * @return boolean
     */
    public function isEmpty() {
        return ($this->size === 0);
    }

    /**
     * Should remove the object from the list if it exists and reset the appropriate
     * values.
     *
     * @param CoreObject $Object
     */
    public function remove(CoreObject $Object) {
        $objectIndex = $this->indexOf($Object);
        if ($objectIndex >= 0) {
            $this->dataStorage[$objectIndex] = null;
            $newArray = array();
            for ($i = 0; $i < $this->size(); $i++) {
                if (isset($this->dataStorage[$i])) {
                    $newArray[] = $this->dataStorage[$i];
                }
            }
            $this->dataStorage = $newArray;
            $newSize = count($this->dataStorage);
            $this->size = $newSize;
        }
    }

    /**
     * If the passed value is a valid, unsigned integer the maxSize is set.
     *
     * Note if this value is set to 0 this indicates that there is no limit on the
     * number of buckets that can be added to the list.  If the maxSize is set
     * AFTER the list has already been created the maxSize will automatically be
     * set to the maxSize of the existing list.  An error message should also be
     * triggered if this happens.
     *
     * @param int $maxSize
     */
    public function setMaxSize($maxSize) {
        $isSizeInteger = is_int($maxSize);
        $isSizeBigEnough = ($maxSize >= 0);
        $isSizeValid = ($isSizeInteger && $isSizeBigEnough);
        if (!$isSizeValid) {
            $maxSize = 0;
            trigger_error('The maximum size for the list was not a valid integer value.', E_USER_WARNING);
        }
        $currentSize = $this->size();
        if ($currentSize > $maxSize) {
            $maxSize = $currentSize;
            trigger_error('The size of the existing list conflicts with the maximum size set.', E_USER_NOTICE);
        }
        $this->maxSize = $maxSize;
    }

    /**
     * @return int
     */
    public function size() {
        return $this->size;
    }

}

// End UniqueList