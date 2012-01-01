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


if (!interface_exists('\\SprayFire\\Core\\Structure\\ObjectMap')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Structure/ObjectMap.php';
}

if (!interface_exists('\\SprayFire\\Bootstrap\\Bootstrapper')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Bootstrap/Bootstrapper.php';
}

if (!interface_exists('\\SprayFire\\Core\\Structure\\Overloadable')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Structure/Overloadable.php';
}

if (!interface_exists('\\SprayFire\\Config\\Configuration')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Config/Configuration.php';
}

if (!class_exists('\\SprayFire\\Core\\CoreObject')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/CoreObject.php';
}

if (!class_exists('\\SprayFire\\Logger\\CoreObject')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Logger/CoreObject.php';
}

if (!class_exists('\\SprayFire\\Bootstrap\\ConfigBootstrap')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Bootstrap/ConfigBootstrap.php';
}


if (!class_exists('\\SprayFire\\Core\\Structure\\DataStorage')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Structure/DataStorage.php';
}

if (!class_exists('\\SprayFire\\Core\\Structure\\ImmutableStorage')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Structure/ImmutableStorage.php';
}

if (!class_exists('\\SprayFire\\Core\\Structure\\GenericMap')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Structure/GenericMap.php';
}

if (!class_exists('\\SprayFire\\Core\\Structure\\RestrictedMap')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Core/Structure/RestrictedMap.php';
}

if (!class_exists('\\SprayFire\\Config\\ArrayConfig')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Config/ArrayConfig.php';
}

if (!class_exists('\\SprayFire\\Config\\JsonConfig')) {
    include \SPRAYFIRE_ROOT . '/libs/SprayFire/Config/JsonConfig.php';
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
class ConfigBootstrapTest extends PHPUnit_Framework_TestCase {

    public function testConfigBootstrap() {

        $configPath = \SPRAYFIRE_ROOT . '/tests/mockframework/app/TestApp/Config/json/test-config.json';

        $configs = array(
            array(
                'config-object' => '\\SprayFire\\Config\\ArrayConfig',
                'config-data' => array('sprayfire' => 'best', 'roll' => 'tide'),
                'map-key' => 'SprayFireRollTide'
            ),
            array(
                'config-object' => '\\SprayFire\\Config\\JsonConfig',
                'config-data' => $configPath,
                'map-key' => 'PrimaryConfig'
            )
        );

        $Log = new \SprayFire\Logger\DevelopmentLogger();
        $Bootstrap = new \SprayFire\Bootstrap\ConfigBootstrap($Log, $configs);
        $Bootstrap->runBootstrap();
        $ConfigMap = $Bootstrap->getConfigs();

        $SprayFireRollTide = $ConfigMap->getObject('SprayFireRollTide');
        $PrimaryConfig = $ConfigMap->getObject('PrimaryConfig');

        $this->assertTrue($ConfigMap instanceof \SprayFire\Core\Structure\RestrictedMap);
        $this->assertTrue($SprayFireRollTide instanceof \SprayFire\Config\ArrayConfig);
        $this->assertTrue($PrimaryConfig instanceof \SprayFire\Config\JsonConfig);

        $this->assertSame('best', $SprayFireRollTide->sprayfire);
        $this->assertSame('tide', $SprayFireRollTide->roll);
        $this->assertSame('Roll Tide!', $PrimaryConfig->app->{'deep-one'}->{'deep-two'}->{'deep-three'}->value);

    }

}