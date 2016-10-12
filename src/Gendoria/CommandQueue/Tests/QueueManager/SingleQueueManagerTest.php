<?php

namespace Gendoria\CommandQueue\Tests\QueueManager;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\ProcessorFactory\ProcessorFactory;
use Gendoria\CommandQueue\QueueManager\SingleQueueManager;
use Gendoria\CommandQueue\SendDriver\DirectProcessingDriver;
use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * Tests for SingleQueueManager.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 * @group CommandQueue
 */
class SingleQueueManagerTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $qm = new SingleQueueManager();
        $processorFactory = new ProcessorFactory();
        $driver = new DirectProcessingDriver();
        $driver->setProcessorFactory($processorFactory);
        $qm->setSendDriver($driver);
        $qm->sendCommand($command);
    }
    
    public function testNoSendDriver()
    {
        $this->setExpectedException(RuntimeException::class, 'Send driver not set');
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $qm = new SingleQueueManager();
        $qm->sendCommand($command);
    }
}
