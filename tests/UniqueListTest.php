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

$testObjectPath = 'TestObject.php';

if (!class_exists('TestObject')) {
    include $testObjectPath;
}

/**
 * This test case will ensure that the framework's implementation of a UniqueList
 * and IteratorList are valid and in proper working order.
 */
class UniqueListTest extends PHPUnit_Framework_TestCase {

    /**
     * Will create a list and run through some of the most basic features; including
     * add(), contains(), size(), isEmpty() and get().
     */
    public function testBasicListMethods() {
        $List = new \libs\sprayfire\datastructs\UniqueList('TestObject');
        $FirstObject = new TestObject();
        $SecondObject = new TestObject();
        $ThirdObject = new TestObject();
        $FourthObject = new TestObject();

        $initialSize = $List->size();
        $expectedInitialSize = 0;
        $this->assertSame($expectedInitialSize, $initialSize);

        $isEmpty = $List->isEmpty();
        $this->assertTrue($isEmpty);

        $List->add($FirstObject);
        $firstSize = $List->size();
        $expectedFirstSize = 1;
        $this->assertSame($expectedFirstSize, $firstSize);

        $containsFirstObject = $List->contains($FirstObject);
        $this->assertTrue($containsFirstObject);

        $isEmpty = $List->isEmpty();
        $this->assertFalse($isEmpty);

        $List->add($SecondObject);
        $secondSize = $List->size();
        $expectedSecondSize = 2;
        $this->assertSame($expectedSecondSize, $secondSize);

        $containsSecondObject = $List->contains($SecondObject);
        $this->assertTrue($containsSecondObject);

        $List->add($ThirdObject);
        $thirdSize = $List->size();
        $expectedThirdSize = 3;
        $this->assertSame($expectedThirdSize, $thirdSize);

        $containsThirdObject = $List->contains($ThirdObject);
        $this->assertTrue($containsThirdObject);

        $containsFourthObject = $List->contains($FourthObject);
        $this->assertFalse($containsFourthObject);

        $ActualFirstObject = $List->get(0);
        $ActualSecondObject = $List->get(1);
        $ActualThirdObject = $List->get(2);

        $this->assertSame($FirstObject, $ActualFirstObject);
        $this->assertSame($SecondObject, $ActualSecondObject);
        $this->assertSame($ThirdObject, $ActualThirdObject);

        $listString = $List->__toString();
        $expectedString = 'libs\sprayfire\datastructs\UniqueList';
        $this->assertEquals($expectedString, $listString);
    }

    /**
     * Will test that the list size can be restricted to a maximum and that adding
     * objects to the list past the maximum size will result in an OutOfRangeException.
     */
    public function testSettingMaxSizeBeforeAdd() {
        $List = new \libs\sprayfire\datastructs\UniqueList('TestObject');
        $List->setMaxSize(3);
        $List->add(new TestObject());
        $List->add(new TestObject());
        $List->add(new TestObject());

        $exceptionThrown = false;
        try {
            $List->add(new TestObject());
        } catch (\OutOfRangeException $Exc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);

        $listSize = $List->size();
        $expectedListSize = 3;
        $this->assertSame($expectedListSize, $listSize);
    }

    /**
     * Will test the restriction of the list, given that there are already a number
     * of objects in the list greater than the maximum set.
     *
     */
    public function testSettingMaxSizeAfterAdd() {
        $List = new \libs\sprayfire\datastructs\UniqueList('TestObject');
        $List->add(new TestObject());
        $List->add(new TestObject());
        $List->add(new TestObject());

        $expectedMaxSize = 3;
        $maxSizeSet = $List->setMaxSize(2);
        $this->assertSame($expectedMaxSize, $maxSizeSet);

        $expectedListSize = 3;
        $listSize = $List->size();
        $this->assertSame($expectedListSize, $listSize);

        $exceptionThrown = false;
        try {
            $List->add(new TestObject());
        } catch (\OutOfRangeException $OorExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    /**
     * Will test restricting a list and a non-integer value is passed; we are checking
     * this by passing a non-numeric string as the `setMaxSize()` argument and then
     * adding 100 objects to the list, a number we would expect to fall below the
     * requested maximum.
     *
     */
    public function testSetInvalidMaxSize() {
        $List = new \libs\sprayfire\datastructs\UniqueList('TestObject');
        $maxSizeSet = $List->setMaxSize('Not an integer value');
        $expectedMaxSize = 0;
        $this->assertSame($expectedMaxSize, $maxSizeSet);
    }

    /**
     * Will test what happens when the parent type passed to the List constructor
     * is not a valid class, in that the class either does not exist or the file
     * holding the class could not be loaded.
     */
    public function testCreateListWithInvalidParentType() {
        $exceptionThrown = false;
        try {
            $List = new \libs\sprayfire\datastructs\UniqueList('IDoNotExist');
        } catch (\InvalidArgumentException $IllegalArgExec) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    /**
     * Will test that an exception is thrown if the parent type passed to the List
     * constructor is blank or has no value.
     */
    public function testCreateListWithNoParentType() {
        $exceptionThrown = false;
        try {
            $List = new \libs\sprayfire\datastructs\UniqueList('');
        } catch (\InvalidArgumentException $IllegalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    /**
     * Will ensure that a UniqueList will properly iterate over its elements; no
     * elements are removed from the List and should be fairly straight forward.
     */
    public function testLoopListWithNoRemove() {
        $List = new \libs\sprayfire\datastructs\UniqueList('TestObject');
        $FirstObject = new TestObject();
        $SecondObject = new TestObject();
        $ThirdObject = new TestObject();

        $List->add($FirstObject);
        $List->add($SecondObject);
        $List->add($ThirdObject);

        $expectedObjects = array($FirstObject, $SecondObject, $ThirdObject);
        $i = 0;
        foreach ($List as $key => $value) {
            $this->assertSame($i, $key);
            $this->assertSame($value, $expectedObjects[$i]);
            $i++;
        }
    }

    /**
     * Will ensure that a UniqueList will properly iterate over its elements; even
     * when a varied number of objects may be added and removed, the objects listed
     * should still be in the appropriate order.
     */
    public function testLoopListWithExternalRemove() {
        $List = new \libs\sprayfire\datastructs\UniqueList('TestObject');
        $FirstObject = new TestObject();
        $SecondObject = new TestObject();
        $ThirdObject = new TestObject();
        $FourthObject = new TestObject();
        $FifthObject = new TestObject();

        $List->add($FirstObject);
        $List->add($SecondObject);
        $List->add($ThirdObject);
        $List->add($FourthObject);

        $secondObjectRemoved = $List->remove($SecondObject);
        $this->assertTrue($secondObjectRemoved);

        $List->set(1, $FifthObject);

        $expectedObjects = array($FirstObject, $FifthObject, $FourthObject);
        $i = 0;
        foreach ($List as $key => $value) {
            $this->assertSame($i, $key);
            $this->assertSame($value, $expectedObjects[$i]);
            $i++;
        }
    }

    /**
     * Will ensure that objects implementing an interface parent type can be added
     * to the list.
     */
    public function testCreatingInterfaceList() {

    }

    /**
     * Ensures that an exception is thrown if the proper index is passed as an
     * argument to get().
     */
    public function testUsingInvalidGetIndex() {
            $exceptionThrown = false;
        try {
            $List = new \libs\sprayfire\datastructs\UniqueList('TestObject');
            $List->add(new TestObject());
            $List->add(new TestObject());
            $Object = $List->get(2);
        } catch (\OutOfRangeException $OorExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    /**
     * Will test that an exception is thrown if an object not implementing the
     * parent type is added to the List.
     */
    public function testAddInvalidObject() {
        
    }

    public function testRemoveInvalidObject() {
        $List = new \libs\sprayfire\datastructs\UniqueList('TestObject');
        $FirstObject = new TestObject();
        $SecondObject = new TestObject();
        $ThirdObject = new TestObject();

        $List->add($FirstObject);
        $List->add($SecondObject);
        $removedThirdObject = $List->remove($ThirdObject);
        $this->assertFalse($removedThirdObject);
    }

}

// End UniqueListTest
