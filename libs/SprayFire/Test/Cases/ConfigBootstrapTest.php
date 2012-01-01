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

namespace SprayFire\Test\Cases;

/**
 * @brief
 */
class ConfigBootstrapTest extends \PHPUnit_Framework_TestCase {

    public function testConfigBootstrap() {

        $configPath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/Config/json/test-config.json';

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