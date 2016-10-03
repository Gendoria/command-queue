<?php

namespace Gendoria\CommandQueue\Tests;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;
use Gendoria\CommandQueue\Exception\MultipleProcessorsException;
use Gendoria\CommandQueue\ProcessorFactory;
use Gendoria\CommandQueue\ProcessorNotFoundException;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * Description of ProcessorFactoryTest
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 * @group CommandQueue
 */
class ProcessorFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testProcessor()
    {
        $service = $this->getMockBuilder(CommandProcessorInterface::class)->getMock();
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
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
        $this->setExpectedException(InvalidArgumentException::class, 'Registering service for non existing command class.');
        $service = $this->getMockBuilder(CommandProcessorInterface::class)->getMock();
        $processor = new ProcessorFactory();
        $processor->registerProcessorForCommand('dummy', $service);
    }
    
    public function testInvalidCommand()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Command class has to implement CommandInterface interface.');
        $service = $this->getMockBuilder(CommandProcessorInterface::class)->getMock();
        $command = $this->getMockBuilder('stdClass')->getMock();
        $processor = new ProcessorFactory();
        $processor->registerProcessorForCommand(get_class($command), $service);
    }
    
    public function testNoProcessor()
    {
        $this->setExpectedException(ProcessorNotFoundException::class);
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $processor = new ProcessorFactory();
        $processor->getProcessor($command);
    }
    
    public function testMultipleProcessorException()
    {
        $this->setExpectedException(MultipleProcessorsException::class);
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $service = $this->getMockBuilder(CommandProcessorInterface::class)->getMock();
        $processor = new ProcessorFactory();
        $processor->registerProcessorForCommand(get_class($command), $service);
        $processor->registerProcessorForCommand(get_class($command), $service);
    }
    
    public function testHasProcessor()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $service = $this->getMockBuilder(CommandProcessorInterface::class)->getMock();
        $processor = new ProcessorFactory();
        $processor->registerProcessorForCommand(get_class($command), $service);
        $this->assertTrue($processor->hasProcessor(get_class($command)));
        $this->assertFalse($processor->hasProcessor('__nonExistingClass__'));
    }
}
