<?php

/*
 * All rights reserved
 * Copyright 2015 Isobar Poland
 */

namespace Gendoria\CommandQueue\Tests;

use Gendoria\CommandQueue\ProcessorFactory;
use Gendoria\CommandQueue\QueueManager\SimpleQueueManager;
use Gendoria\CommandQueue\SendDriver\DirectProcessingDriver;
use PHPUnit_Framework_TestCase;

/**
 * Description of SimpleQueueManagerTest
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 * @group CommandQueue
 */
class SimpleQueueManagerTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $command = $this->getMock('\Gendoria\CommandQueue\Command\CommandInterface');
        $qm = new SimpleQueueManager();
        $processorFactory = new ProcessorFactory();
        $driver = new DirectProcessingDriver();
        $driver->setProcessorFactory($processorFactory);
        $qm->setSendDriver($driver);
        $qm->sendCommand($command);
    }
    
    public function testNoSendDriver()
    {
        $this->setExpectedException('\RuntimeException', 'Send driver not set');
        $command = $this->getMock('\Gendoria\CommandQueue\Command\CommandInterface');
        $qm = new SimpleQueueManager();
        $qm->sendCommand($command);
    }
}
