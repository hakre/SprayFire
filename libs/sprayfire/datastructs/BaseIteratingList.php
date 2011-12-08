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
 * This is a base list, it provides a means to store the objects and perform basic
 * common functionality on those elements.
 *
 * This list also implements the methods necessary for the list to be iterated
 * over.  This list should be able to be used in a foreach() loop
 */
abstract class BaseIteratingList extends CoreObject implements DataList {

    /**
     * The array structure used to store the objects
     *
     * @var array
     */
    protected $dataStorage = array();

    /**
     * The number of elements in the list.
     *
     * This value also represents the index used for the next saved element.
     *
     * @var int
     */
    private $size = 0;

    /**
     * An object used to verify that the type of object being added to the list
     * implements the correct interface or extends the correct class.
     *
     * @var ObjectTypeValidator
     */
    private $TypeValidator;

    /**
     * The position of the pointer used for the iterator functions.
     *
     * @var int
     */
    private $currentPointer = 0;

    public function __construct($parentType) {
        $this->TypeValidator = new ObjectTypeValidator($parentType);
    }

    public function rewind() {
        $this->currentPointer = 0;
    }

    /**
     * @return CoreObject
     */
    public function current() {
        return $this->dataStorage[$this->currentPointer];
    }

    /**
     * @return int
     */
    public function key() {
        return $this->currentPointer;
    }

    public function next() {
        ++$this->currentPointer;
    }

    /**
     * @return boolean
     */
    public function valid() {
        return isset($this->dataStorage[$this->currentPointer]);
    }

    /**
     * Returns the number representing the index for the passed element, or -1 if
     * the element could not be found.
     *
     * @param CoreObject $Object
     * @return int
     */
    public function indexOf(Object $Object) {
        $index = false;
        for ($i = 0; $i < $this->size(); $i++) {
            $ListedObject = $this->dataStorage[$i];
            if ($Object->equals($ListedObject)) {
                $index = $i;
                break;
            }
        }
        return $index;
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
    protected function throwExceptionIfIndexOutOfRange($index) {
        if (!is_int($index) || $index < 0 || $index >= $this->size()) {
            throw new OutOfRangeException('The requested index is not valid.');
        }
    }

    /**
     * @param CoreObject $Object
     * @throws InvalidArgumentException
     */
    protected function throwExceptionIfObjectNotParentType(Object $Object) {
        $isObjectValid = $this->TypeValidator->isObjectParentType($Object);
        if (!$isObjectValid) {
            throw new InvalidArgumentException('The object passed does not implement/extend ' . $this->TypeValidator->getParentType());
        }
    }

    /**
     * Determines whether or not the list contains the given object.
     *
     * @param CoreObject $Object
     * @return boolean
     */
    public function contains(Object $Object) {
        $objectContained = false;
        $objectIndex = $this->indexOf($Object);
        if ($objectIndex !== false) {
            $objectContained = true;
        }
        return $objectContained;
    }

    /**
     * @return boolean
     */
    public function isEmpty() {
        return ($this->size === 0);
    }

    /**
     * @return int
     */
    public function size() {
        return $this->size;
    }

    /**
     * Will set the size of the list to the proper amount of elements in the list,
     * should be called if an object is removed.
     */
    protected function recalculateSize() {
        $this->size = count($this->dataStorage);
    }

}

// End BaseIteratingList
