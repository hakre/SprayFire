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
 */

namespace libs\sprayfire\datastructs;

/**
 * This class is a data storage that also acts as an iterator, allowing objects
 * extending this class to be used in a foreach loop.
 *
 * This code is heavily influenced by the Zend Framework 2 Zend\Config\Config class.
 * https://github.com/zendframework/zf2/blob/master/library/Zend/Config/Config.php
 */
abstract class MutableIterator extends \libs\sprayfire\datastructs\MutableStorage implements \Iterator {

    /**
     * Holds the number of buckets being stored by $data
     *
     * @var int
     */
    private $count = 0;

    /**
     * Holds the number of the current internal pointer for the $data array.
     *
     * @var int
     */
    private $index = 0;

    /**
     * Is flagged true when elements are removed from the storage during a loop
     * so that the next iteration over the array will be skipped.
     *
     * @var boolean
     */
    protected $skipNextIteration = false;

    /**
     * Overridden to ensure that the iterator can set the proper count for the storage
     * when the object is created.
     *
     * @param array $data
     */
    public function __construct(array $data) {
        parent::__construct($data);
        $this->countData();
    }

    /**
     * Will return whetever value is held by the current pointer of the data storage.
     *
     * @return mixed
     */
    public function current() {
        $this->skipNextIteration = false;
        return \current($this->data);
    }

    /**
     * Will return the key that is storing the current value; this can be an int
     * or a string.
     *
     * @return mixed
     */
    public function key() {
        return \key($this->data);
    }

    /**
     * Will advance the internal pointer of the array.
     *
     * @return void
     */
    public function next() {
        if ($this->skipNextIteration) {
            $this->skipNextIteration = false;
            return;
        }
        \next($this->data);
        $this->index++;
    }

    /**
     * Will reset the internal array pointer and set the Iterator to be looped
     * through.
     */
    public function rewind() {
        $this->skipNextIteration = false;
        \reset($this->data);
        $this->index = 0;
    }

    /**
     * Will return whether or not the loop should continue; once the index is >=
     * count this function will return false.
     *
     * @return boolean
     */
    public function valid() {
        return $this->index < $this->count;
    }

    /**
     * Overrides the inherited set method to ensure the number of buckets in the
     * storage are being counted.
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    protected function set($key, $value) {
        $setValue = parent::set($key, $value);
        $this->countData();
        return $setValue;
    }

    /**
     * Overrides the inherited removeKey method to ensure that the new number of
     * buckets are counted and to ensure the skipNextIteration flag is set to true.
     *
     * @param string $key
     */
    protected function removeKey($key) {
        parent::removeKey($key);
        $this->countData();
        $this->skipNextIteration = true;
    }

    /**
     * Provides access to extending classes to retrieve the number of buckets stored
     * in this iterator.
     *
     * @return int
     */
    protected function getCount() {
        return $this->count;
    }

    /**
     * Provides access to extending classes to recalibrate the number of buckets
     * stored in this iterator.
     */
    protected function countData() {
        $this->count = \count($this->data);
    }

}

// End MutableIterator
