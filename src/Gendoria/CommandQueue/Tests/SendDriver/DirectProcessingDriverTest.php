<?php

namespace Gendoria\CommandQueue\Tests\SendDriver;

use Exception;
use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;
use Gendoria\CommandQueue\ProcessorFactory\Exception\ProcessorNotFoundException;
use Gendoria\CommandQueue\ProcessorFactory\ProcessorFactory;
use Gendoria\CommandQueue\SendDriver\DirectProcessingDriver;
use Gendoria\CommandQueue\Serializer\Exception\UnserializeErrorException;
use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;

/**
 * Description of DirectProcessingDriverTest
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 * @group CommandQueue
 */
class DirectProcessingDriverTest extends PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $service = $this->getMockBuilder(CommandProcessorInterface::class)->getMock();
        $service->expects($this->any())
            ->method('supports')
            ->with($command)
            ->will($this->returnValue(true));
        
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->never())
            ->method('error');
        $processorFactory = new ProcessorFactory();
        $processorFactory->registerProcessorForCommand(get_class($command), $service);
        $driver = new DirectProcessingDriver($processorFactory);
        $driver->setProcessorFactory($processorFactory);
        $driver->setLogger($logger);
        
        $driver->send($command);
    }
    
    public function testSendNoProcessor()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->once())
            ->method('error')
            ->with('Exception while sending command: No processor registered for given command class: '.get_class($command));
        
        $processorFactory = new ProcessorFactory();
        $driver = new DirectProcessingDriver($processorFactory);
        $driver->setProcessorFactory($processorFactory);
        $driver->setLogger($logger);
        
        $driver->send($command);
    }    
    
    public function testSendProcessorException()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $service = $this->getMockBuilder(CommandProcessorInterface::class)->getMock();
        $service->expects($this->any())
            ->method('supports')
            ->with($command)
            ->will($this->returnValue(true));
        
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $logger->expects($this->once())
            ->method('error')
            ->with('Exception while sending command: Dummy exception');
        $service->expects($this->once())
            ->method('process')
            ->with($command)
            ->willThrowException(new Exception("Dummy exception"));
            ;
        $processorFactory = new ProcessorFactory();
        $processorFactory->registerProcessorForCommand(get_class($command), $service);
        $driver = new DirectProcessingDriver($processorFactory);
        $driver->setProcessorFactory($processorFactory);
        $driver->setLogger($logger);
        
        $driver->send($command);
    }    
    
    public function testNoProcessor()
    {
        $this->setExpectedException(ProcessorNotFoundException::class);
        $processorFactory = new ProcessorFactory();
        $driver = new DirectProcessingDriver($processorFactory);
        $driver->setProcessorFactory($processorFactory);
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        
        $driver->process($command);
    }
    
    public function testIncorrectCommand()
    {
        $this->setExpectedException(UnserializeErrorException::class);
        $processorFactory = new ProcessorFactory();
        $driver = new DirectProcessingDriver($processorFactory);
        $driver->setProcessorFactory($processorFactory);
        
        $driver->process("Not a command");
    }        
}
