<?php

namespace Gendoria\CommandQueue\Tests;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\ProcessorFactory;
use Gendoria\CommandQueue\QueueManager\SimpleQueueManager;
use Gendoria\CommandQueue\SendDriver\DirectProcessingDriver;
use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * Description of SimpleQueueManagerTest
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 * @group CommandQueue
 */
class SimpleQueueManagerTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $qm = new SimpleQueueManager();
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
        $qm = new SimpleQueueManager();
        $qm->sendCommand($command);
    }
}
