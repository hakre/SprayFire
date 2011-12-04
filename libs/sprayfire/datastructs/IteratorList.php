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
 * This is a base list, it provides a means to store the objects and to keep up
 * with the size of the list.
 *
 * This list also implements the methods necessary for the list to be iterated
 * over.  This list should be able to be used in a foreach() loop
 */
abstract class IteratorList extends CoreObject implements DataList {

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
    protected $size = 0;

    /**
     * The position of the pointer used for the iterator functions.
     *
     * @var int
     */
    private $currentPointer = 0;

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


}

// End IteratorList
