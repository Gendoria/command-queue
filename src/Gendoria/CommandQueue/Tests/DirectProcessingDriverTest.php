<?php

/*
 * All rights reserved
 * Copyright 2015 Isobar Poland
 */

namespace Gendoria\CommandQueue\Tests;

use Gendoria\CommandQueue\ProcessorFactory;
use Gendoria\CommandQueue\SendDriver\DirectProcessingDriver;
use PHPUnit_Framework_TestCase;
use Psr\Log\NullLogger;

/**
 * Description of DirectProcessingDriverTest
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 * @group CommandQueue
 */
class DirectProcessingDriverTest extends PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $command = $this->getMock('\Gendoria\CommandQueue\Command\CommandInterface');
        $service = $this->getMock('\Gendoria\CommandQueue\CommandProcessorInterface');
        $service->expects($this->any())
            ->method('supports')
            ->with($command)
            ->will($this->returnValue(true));
        
        $logger = $this->getMock('\Psr\Log\LoggerInterface');
        $logger->expects($this->never())
            ->method('error');
        $processorFactory = new ProcessorFactory();
        $processorFactory->registerProcessorForCommand(get_class($command), $service);
        $driver = new DirectProcessingDriver();
        $driver->setProcessorFactory($processorFactory);
        $driver->setLogger($logger);
        
        $driver->send($command);
    }
    
    public function testSendNoProcessor()
    {
        $command = $this->getMock('\Gendoria\CommandQueue\Command\CommandInterface');
        $logger = $this->getMock('\Psr\Log\LoggerInterface');
        $logger->expects($this->once())
            ->method('error')
            ->with('Exception while sending command: No processor registered for given command class: '.  get_class($command));
        
        $processorFactory = new ProcessorFactory();
        $driver = new DirectProcessingDriver();
        $driver->setProcessorFactory($processorFactory);
        $driver->setLogger($logger);
        
        $driver->send($command);
    }    
    
    public function testSendProcessorException()
    {
        $command = $this->getMock('\Gendoria\CommandQueue\Command\CommandInterface');
        $service = $this->getMock('\Gendoria\CommandQueue\CommandProcessorInterface');
        $service->expects($this->any())
            ->method('supports')
            ->with($command)
            ->will($this->returnValue(true));
        
        $logger = $this->getMock('\Psr\Log\LoggerInterface');
        $logger->expects($this->once())
            ->method('error')
            ->with('Exception while sending command: Dummy exception');
        $service->expects($this->once())
            ->method('process')
            ->with($command)
            ->willThrowException(new \Exception("Dummy exception"));
            ;
        $processorFactory = new ProcessorFactory();
        $processorFactory->registerProcessorForCommand(get_class($command), $service);
        $driver = new DirectProcessingDriver();
        $driver->setProcessorFactory($processorFactory);
        $driver->setLogger($logger);
        
        $driver->send($command);
    }    
    
    public function testNoProcessor()
    {
        $this->setExpectedException('\Gendoria\CommandQueue\ProcessorNotFoundException');
        $processorFactory = new ProcessorFactory();
        $driver = new DirectProcessingDriver();
        $driver->setProcessorFactory($processorFactory);
        $command = $this->getMock('\Gendoria\CommandQueue\Command\CommandInterface');
        
        $driver->process($command);
    }    
}
