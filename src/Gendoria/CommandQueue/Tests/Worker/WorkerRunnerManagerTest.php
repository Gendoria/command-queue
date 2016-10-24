<?php

namespace Gendoria\CommandQueue\Tests\Worker;

use Gendoria\CommandQueue\Worker\WorkerRunnerInterface;
use Gendoria\CommandQueue\Worker\WorkerRunnerManager;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * Tests for worker runner manager.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class WorkerRunnerManagerTest extends PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $service = $this->getMockBuilder(WorkerRunnerInterface::class)->getMock();
        $manager = new WorkerRunnerManager();
        $this->assertFalse($manager->has('test'));
        $manager->addRunner('test', $service);
        $this->assertTrue($manager->has('test'));
        $this->assertEquals(array('test'), $manager->getRunners());
    }
    
    public function testRun()
    {
        $service = $this->getMockBuilder(WorkerRunnerInterface::class)->getMock();
        $service->expects($this->once())
            ->method('run');
        $manager = new WorkerRunnerManager();
        $manager->addRunner('test', $service);
        $manager->run('test');
    }    
    
    public function testRunException()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'No runner service registered for provided name.');
        $manager = new WorkerRunnerManager();
        $manager->run('test');
    }    
}
