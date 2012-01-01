<?php

/**
 * @file
 * @brief
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
if (!interface_exists('\\SprayFire\\Core\\Object')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Object.php';
}
if (!class_exists('\\SprayFire\\Core\\CoreObject')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/CoreObject.php';
}
if (!interface_exists('\\SprayFire\\Logger\\Log')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Logger/Log.php';
}
if (!class_exists('\\SprayFire\\Logger\\DevelopmentLogger')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Logger/DevelopmentLogger.php';
}

/**
 * @brief
 */
class DevelopmentLoggerTest extends PHPUnit_Framework_TestCase {

    public function testDevelopmentLogger() {
        $Logger = new \SprayFire\Logger\DevelopmentLogger();
        $timestamp = 'something';

        $name = 'charles';
        $love = 'dyana';
        $roll = 'tide';
        $info = \compact('name', 'love', 'roll');

        $Logger->log($timestamp, $info);

        $expected = array();
        $expected[0]['timestamp'] = $timestamp;
        $expected[0]['info'] = $info;
        $actual = $Logger->getMessages();
        $this->assertSame($expected, $actual);

        $timestamp = 'something else';

        $name = 'dyana';
        $love = 'charles';
        $roll = 'tide roll';
        $info = \compact('name', 'love', 'roll');

        $Logger->log($timestamp, $info);

        $expected[1]['timestamp'] = $timestamp;
        $expected[1]['info'] = $info;
        $actual = $Logger->getMessages();
        $this->assertSame($expected, $actual);
    }

}