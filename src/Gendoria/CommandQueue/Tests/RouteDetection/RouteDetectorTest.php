<?php

namespace Gendoria\CommandQueue\Tests\RouteDetection;

use Gendoria\CommandQueue\RouteDetection\Detector\RouteDetector;
use Gendoria\CommandQueue\Tests\RouteDetection\Fixtures\DummyChildClass;
use Gendoria\CommandQueue\Tests\RouteDetection\Fixtures\DummyChildInterface;
use Gendoria\CommandQueue\Tests\RouteDetection\Fixtures\DummyClass;
use Gendoria\CommandQueue\Tests\RouteDetection\Fixtures\DummyInterface;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * Description of RouteDetectorTest.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 * @group RouteDetection
 */
class RouteDetectorTest extends PHPUnit_Framework_TestCase
{
    public function testExceptionForNonClass()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $detector = new RouteDetector();
        $detector->detect('notaclass');
    }

    public function testDefaultRoute()
    {
        $detector = new RouteDetector();
        $detector->setDefault('test');
        $this->assertEquals('test', $detector->detect(DummyClass::class));
    }

    public function testSimpleRoute()
    {
        $detector = new RouteDetector();
        $detector->setDefault('default');
        $detector->addRoute(DummyClass::class, 'test');
        $this->assertEquals('test', $detector->detect(DummyClass::class));
    }

    public function testRegexpRoute()
    {
        $detector = new RouteDetector();
        $detector->setDefault('default');
        $detector->addRoute(substr(DummyClass::class, 5).'*', 'test');
        $this->assertEquals('test', $detector->detect(DummyClass::class));
    }

    public function testBaseClassRoute()
    {
        $detector = new RouteDetector();
        $detector->setDefault('default');
        $detector->addRoute(DummyClass::class, 'test');
        $this->assertEquals('test', $detector->detect(DummyChildClass::class));
    }

    public function testClassRoutePrecedenceOverBaseClassRoute()
    {
        $detector = new RouteDetector();
        $detector->setDefault('default');
        $detector->addRoute(DummyClass::class, 'test2');
        $detector->addRoute(DummyChildClass::class, 'test');
        $this->assertEquals('test', $detector->detect(DummyChildClass::class));
    }

    public function testInterfaceRoute()
    {
        $detector = new RouteDetector();
        $detector->setDefault('default');
        $detector->addRoute(DummyChildInterface::class, 'test');
        $this->assertEquals('test', $detector->detect(DummyChildClass::class));
    }

    public function testBaseClassInterfaceRoute()
    {
        $detector = new RouteDetector();
        $detector->setDefault('default');
        $detector->addRoute(DummyInterface::class, 'test');
        $this->assertEquals('test', $detector->detect(DummyChildClass::class));
    }

    public function testBaseClassPrecedenceOverInterface()
    {
        $detector = new RouteDetector();
        $detector->setDefault('default');
        $detector->addRoute(DummyInterface::class, 'test2');
        $detector->addRoute(DummyClass::class, 'test');
        $this->assertEquals('test', $detector->detect(DummyChildClass::class));
    }

    public function testClassInterfacePrecedenceOverBaseClassInterface()
    {
        $detector = new RouteDetector();
        $detector->setDefault('default');
        $detector->addRoute(DummyChildInterface::class, 'test');
        $detector->addRoute(DummyInterface::class, 'test2');
        $this->assertEquals('test', $detector->detect(DummyChildClass::class));
    }
}
