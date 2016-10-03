<?php

namespace Gendoria\CommandQueue\Tests\RouteDetection;

use Gendoria\CommandQueue\RouteDetection\Detector\CachedRouteDetector;
use Gendoria\CommandQueue\Tests\RouteDetection\Fixtures\DummyChildClass;
use Gendoria\CommandQueue\Tests\RouteDetection\Fixtures\DummyInterface;
use PHPUnit_Framework_TestCase;
use ReflectionObject;

/**
 * Description of RouteDetectorTest.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
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
        $this->assertNotEmpty($cache->getValue($detector));
        //This should go from cache
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
