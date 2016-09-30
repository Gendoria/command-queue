<?php

/**
 * All rights reserved
 * Copyright 2016 Isobar Poland.
 */

namespace Gendoria\CommandQueue\RouteDetection\Tests;

use InvalidArgumentException;
use Gendoria\CommandQueue\RouteDetection\RouteDetector;
use Gendoria\CommandQueue\RouteDetection\Tests\Fixtures\DummyChildClass;
use Gendoria\CommandQueue\RouteDetection\Tests\Fixtures\DummyChildInterface;
use Gendoria\CommandQueue\RouteDetection\Tests\Fixtures\DummyClass;
use Gendoria\CommandQueue\RouteDetection\Tests\Fixtures\DummyInterface;
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
