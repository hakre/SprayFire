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
 * iteration responsibilities being handled by its immediate superclass.
 *
 * This data structure guarantees that the objects added to it implement the appropriate
 * interface or extends the appropriate class.  This data structure will also ensure
 * that only unique objects are added to the List.  The only means in which to set the
 * type of objects stored in the class is through the constructor.  If you want
 * the list to store different types of objects you will need to create a new one.
 *
 * @see ObjectTypeValidator
 */
class UniqueList extends BaseIteratingList {

    /**
     * A zero in the $maxSize property value means the list may have an unlimited
     * number of objects.
     *
     * @var int
     */
    private $maxSize;

    /**
     * Will add the passed object to the list, given (a) there are enough empty buckets
     * in the list, (b) the object properly implements the $parentType and (c) the
     * object does not already exist in the list.
     *
     * @param Object $Object
     * @throws OutOfRangeException
     *         IllegalArgumentException
     */
    public function add(Object $Object) {
        $this->throwExceptionIfNoAvailableBuckets();
        $this->throwExceptionIfObjectNotParentType($Object);
        $isObjectInList = $this->contains($Object);
        if (!$isObjectInList) {
            $this->_add($Object);
        }
    }

    /**
     * Should change the index of the given list to the object passed.
     *
     * @param int $index
     * @param Object $Object
     * @throws OutOfRangeException
     *         IllegalArgumentException
     */
    public function set($index, Object $Object) {
        $this->throwExceptionIfIndexOutOfRange($index);
        $this->throwExceptionIfObjectNotParentType($Object);
        $this->dataStorage[$index] = null;
        $this->dataStorage[$index] = $Object;
    }

    /**
     * Will add the passed object to the storage and increment the size of the
     * list.
     *
     * @param Object $Object
     */
    private function _add(Object $Object) {
        $arrayIndex = $this->size();
        $this->dataStorage[$arrayIndex] = $Object;
        $this->recalculateSize();
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
     * Will return whether or not the list has enough buckets to add 1 additional
     * element.
     *
     * Note, if the $maxSize is set to 0 then there will always be available buckets
     * for the list.
     *
     * @return boolean
     */
    private function hasAvailableBuckets() {
        $hasBuckets = true;
        if ($this->maxSize > 0) {
            if ($this->size() >= $this->maxSize) {
                $hasBuckets = false;
            }
        }
        return $hasBuckets;
    }

    /**
     * Should remove the object from the list if it exists and resize the array.
     *
     * Please note that it is highly unrecommended that you remove objects from a
     * list while looping through the list, for example in a foreach() loop.  The
     * initial implementation simply wasn't sufficient to handle this, please avoid
     * doing so.  This feature may be added in a future release.
     *
     * @param Object $Object
     * @return boolean
     */
    public function remove(Object $Object) {
        $objectIndex = $this->indexOf($Object);
        if ($objectIndex !== false) {
            unset($this->dataStorage[$objectIndex]);
            $this->dataStorage = array_values($this->dataStorage);
            $this->recalculateSize();
            return true;
        }
        return false;
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
     * @return int
     */
    public function setMaxSize($maxSize) {
        $isSizeInteger = is_int($maxSize);
        $isSizeBigEnough = ($maxSize >= 0);
        $isSizeValid = ($isSizeInteger && $isSizeBigEnough);
        if (!$isSizeValid) {
            $maxSize = 0;
            error_log('The maximum size for the list was not a valid integer value, no maximum size set.');
        }
        $currentSize = $this->size();
        if ($currentSize > $maxSize) {
            $maxSize = $currentSize;
            error_log('The current size of the list is greater than the maximum size set, maximum size set to current size.');
        }
        return $this->maxSize = $maxSize;
    }

}

// End UniqueList