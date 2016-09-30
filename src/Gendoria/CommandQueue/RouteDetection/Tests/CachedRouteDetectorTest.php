<?php

/**
 * All rights reserved
 * Copyright 2016 Isobar Poland.
 */

namespace Gendoria\CommandQueue\RouteDetection\Tests;

use Gendoria\CommandQueue\RouteDetection\CachedRouteDetector;
use Gendoria\CommandQueue\RouteDetection\Tests\Fixtures\DummyChildClass;
use Gendoria\CommandQueue\RouteDetection\Tests\Fixtures\DummyInterface;
use PHPUnit_Framework_TestCase;
use ReflectionObject;

/**
 * Description of RouteDetectorTest.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 * @group RouteDetection
 */
class CachedRouteDetectorTest extends PHPUnit_Framework_TestCase
{
    public function testAddRouteClear()
    {
        $detector = new CachedRouteDetector();
        $reflectionObject = new ReflectionObject($detector);
        $cache = $reflectionObject->getProperty('cachedRoutes');
        $cache->setAccessible(true);
        $detector->addRoute(DummyInterface::class, 'test');
        $detector->detect(DummyChildClass::class);
        $this->assertEquals(array(DummyChildClass::class => 'test'), $cache->getValue($detector));
        $detector->addRoute(DummyInterface::class, 'test2');
        $this->assertEmpty($cache->getValue($detector));
    }

    public function testAddRouteNoClearOnSame()
    {
        $detector = new CachedRouteDetector();
        $reflectionObject = new ReflectionObject($detector);
        $cache = $reflectionObject->getProperty('cachedRoutes');
        $cache->setAccessible(true);
        $detector->addRoute(DummyInterface::class, 'test');
        $detector->detect(DummyChildClass::class);
        $this->assertEquals(array(DummyChildClass::class => 'test'), $cache->getValue($detector));
        $detector->addRoute(DummyInterface::class, 'test');
        $this->assertEquals(array(DummyChildClass::class => 'test'), $cache->getValue($detector));
    }

    public function testSetDefaultClear()
    {
        $detector = new CachedRouteDetector();
        $reflectionObject = new ReflectionObject($detector);
        $cache = $reflectionObject->getProperty('cachedRoutes');
        $cache->setAccessible(true);
        $detector->setDefault('test');
        $detector->detect(DummyChildClass::class);
        $this->assertEquals(array(DummyChildClass::class => 'test'), $cache->getValue($detector));
        $detector->setDefault('test2');
        $this->assertEmpty($cache->getValue($detector));
    }

    public function testSetDefaultNoClearOnSame()
    {
        $detector = new CachedRouteDetector();
        $reflectionObject = new ReflectionObject($detector);
        $cache = $reflectionObject->getProperty('cachedRoutes');
        $cache->setAccessible(true);
        $detector->setDefault('test');
        $detector->detect(DummyChildClass::class);
        $this->assertEquals(array(DummyChildClass::class => 'test'), $cache->getValue($detector));
        $detector->setDefault('test');
        $this->assertEquals(array(DummyChildClass::class => 'test'), $cache->getValue($detector));
    }
}
