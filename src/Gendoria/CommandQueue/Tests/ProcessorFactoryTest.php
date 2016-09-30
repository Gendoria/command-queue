<?php

/*
 * All rights reserved
 * Copyright 2015 Isobar Poland
 */

namespace Gendoria\CommandQueue\Tests;

use Gendoria\CommandQueue\ProcessorFactory;
use PHPUnit_Framework_TestCase;

/**
 * Description of ProcessorFactoryTest
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 * @group CommandQueue
 */
class ProcessorFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testProcessor()
    {
        $service = $this->getMockBuilder('\Gendoria\CommandQueue\CommandProcessorInterface')->getMock();
        $command = $this->getMockBuilder('\Gendoria\CommandQueue\Command\CommandInterface')->getMock();
        $service->expects($this->any())
            ->method('supports')
            ->with($command)
            ->will($this->returnValue(true));
        
        $processor = new ProcessorFactory();
        $processor->registerProcessorForCommand(get_class($command), $service);
        $this->assertEquals($service, $processor->getProcessor($command));
    }
    
    public function testNotExistingCommand()
    {
        $this->setExpectedException('\InvalidArgumentException', 'Registering service for non existing command class.');
        $service = $this->getMockBuilder('\Gendoria\CommandQueue\CommandProcessorInterface')->getMock();
        $processor = new ProcessorFactory();
        $processor->registerProcessorForCommand('dummy', $service);
    }
    
    public function testInvalidCommand()
    {
        $this->setExpectedException('\InvalidArgumentException', 'Command class has to implement CommandInterface interface.');
        $service = $this->getMockBuilder('\Gendoria\CommandQueue\CommandProcessorInterface')->getMock();
        $command = $this->getMockBuilder('stdClass')->getMock();
        $processor = new ProcessorFactory();
        $processor->registerProcessorForCommand(get_class($command), $service);
    }
    
    public function testNoProcessor()
    {
        $this->setExpectedException('\Gendoria\CommandQueue\ProcessorNotFoundException');
        $command = $this->getMockBuilder('\Gendoria\CommandQueue\Command\CommandInterface')->getMock();
        $processor = new ProcessorFactory();
        $processor->getProcessor($command);
    }
}
