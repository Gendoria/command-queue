<?php

namespace Gendoria\CommandQueue\Tests\Worker;

use Exception;
use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;
use Gendoria\CommandQueue\ProcessorFactoryInterface;
use Gendoria\CommandQueue\ProcessorNotFoundException;
use Gendoria\CommandQueue\Worker\BaseWorker;
use Gendoria\CommandQueue\Worker\Exception\ProcessorErrorException;
use Gendoria\CommandQueue\Worker\Exception\TranslateErrorException;
use PHPUnit_Framework_MockObject_Generator;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

/**
 * Description of BaseWorkerTest
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
class BaseWorkerTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectProcess()
    {
        /* @var $processorFactory PHPUnit_Framework_MockObject_MockObject|PHPUnit_Framework_MockObject_Generator|ProcessorFactoryInterface */
        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        
        $mockBaseBuilder = $this->getMockBuilder(BaseWorker::class)
            ->enableProxyingToOriginalMethods()
            ->enableOriginalConstructor()
            ->setConstructorArgs(array($processorFactory))
            ->setMethods(array('beforeTranslateHook', 'beforeGetProcessorHook', 'beforeProcessHook', 'translateCommand', 'afterProcessHook'));
        
        $commandData = "DummyData";
        
        $processor = $this->getMockBuilder(CommandProcessorInterface::class)->getMock();

        $processorFactory->expects($this->once())
            ->method('getProcessor')
            ->with()
            ->will($this->returnValue($processor));
        
        /* @var $mock PHPUnit_Framework_MockObject_MockObject|PHPUnit_Framework_MockObject_Generator|BaseWorker */
        $mock = $mockBaseBuilder->getMockForAbstractClass();
        $mock->setProcessorFactory($processorFactory);
        
        $mock->expects($this->once())
            ->method('translateCommand')
            ->will($this->returnValue($command));
        
        $mock->expects($this->once())->method('beforeTranslateHook')->with("DummyData");
        $mock->expects($this->once())->method('beforeGetProcessorHook')->with($command);
        $mock->expects($this->once())->method('beforeProcessHook')->with($command, $processor);
        $mock->expects($this->once())
            ->method('afterProcessHook')
            ->with($command, $processor);
        
        $mock->process($commandData);        
    }
    
    public function testInvalidTranslation()
    {
        $this->setExpectedException(TranslateErrorException::class, "Dummy exception");
        /* @var $processorFactory PHPUnit_Framework_MockObject_MockObject|PHPUnit_Framework_MockObject_Generator|ProcessorFactoryInterface */
        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        
        $mockBaseBuilder = $this->getMockBuilder(BaseWorker::class)
            ->enableProxyingToOriginalMethods()
            ->enableOriginalConstructor()
            ->setConstructorArgs(array($processorFactory))
            ->setMethods(array('beforeTranslateHook', 'beforeGetProcessorHook', 'beforeProcessHook', 'translateCommand', 'afterProcessHook'));
        
        $commandData = "DummyData";
        
        /* @var $mock PHPUnit_Framework_MockObject_MockObject|PHPUnit_Framework_MockObject_Generator|BaseWorker */
        $mock = $mockBaseBuilder->getMockForAbstractClass();
        
        $translateException = new Exception("Dummy exception");
        $mock->expects($this->once())
            ->method('translateCommand')
            ->will($this->throwException($translateException));
        
        $mock->expects($this->once())->method('beforeTranslateHook')->with("DummyData");
        
        try {
            $mock->process($commandData);
        } catch (TranslateErrorException $e) {
            $this->assertEquals($translateException, $e->getPrevious());
            $this->assertEquals($commandData, $e->getCommandData());
            throw $e;
        }
    }
    
    public function testNoProcessor()
    {
        $this->setExpectedException(ProcessorNotFoundException::class, "Not found");
        /* @var $processorFactory PHPUnit_Framework_MockObject_MockObject|PHPUnit_Framework_MockObject_Generator|ProcessorFactoryInterface */
        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        
        $mockBaseBuilder = $this->getMockBuilder(BaseWorker::class)
            ->enableProxyingToOriginalMethods()
            ->enableOriginalConstructor()
            ->setConstructorArgs(array($processorFactory))
            ->setMethods(array('beforeTranslateHook', 'beforeGetProcessorHook', 'beforeProcessHook', 'translateCommand', 'afterProcessHook'));
        
        $commandData = "DummyData";
        
        $procesorException = new ProcessorNotFoundException("Not found");
        $processorFactory->expects($this->once())
            ->method('getProcessor')
            ->with()
            ->will($this->throwException($procesorException));
        
        /* @var $mock PHPUnit_Framework_MockObject_MockObject|PHPUnit_Framework_MockObject_Generator|BaseWorker */
        $mock = $mockBaseBuilder->getMockForAbstractClass();
        
        $mock->expects($this->once())
            ->method('translateCommand')
            ->will($this->returnValue($command));
        
        $mock->expects($this->once())->method('beforeTranslateHook')->with("DummyData");
        $mock->expects($this->once())->method('beforeGetProcessorHook')->with($command);
        
        $mock->process($commandData);
    }
    
    public function testProcessorError()
    {
        $this->setExpectedException(ProcessorErrorException::class, "Processor exception");
        /* @var $processorFactory PHPUnit_Framework_MockObject_MockObject|PHPUnit_Framework_MockObject_Generator|ProcessorFactoryInterface */
        $processorFactory = $this->getMockBuilder(ProcessorFactoryInterface::class)->getMock();
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        
        $mockBaseBuilder = $this->getMockBuilder(BaseWorker::class)
            ->enableProxyingToOriginalMethods()
            ->enableOriginalConstructor()
            ->setConstructorArgs(array($processorFactory))
            ->setMethods(array('beforeTranslateHook', 'beforeGetProcessorHook', 'beforeProcessHook', 'translateCommand', 'afterProcessHook'));
        
        $commandData = "DummyData";
        
        $exception = new Exception("Processor exception");
        $processor = $this->getMockBuilder(CommandProcessorInterface::class)->getMock();
        $processor->expects($this->once())
            ->method('process')
            ->with($command)
            ->will($this->throwException($exception));

        $processorFactory->expects($this->once())
            ->method('getProcessor')
            ->with()
            ->will($this->returnValue($processor));
        
        /* @var $mock PHPUnit_Framework_MockObject_MockObject|PHPUnit_Framework_MockObject_Generator|BaseWorker */
        $mock = $mockBaseBuilder->getMockForAbstractClass();
        
        $mock->expects($this->once())
            ->method('translateCommand')
            ->will($this->returnValue($command));
        
        $mock->expects($this->once())->method('beforeTranslateHook')->with("DummyData");
        $mock->expects($this->once())->method('beforeGetProcessorHook')->with($command);
        $mock->expects($this->once())->method('beforeProcessHook')->with($command, $processor);
        $mock->expects($this->never())->method('afterProcessHook');
        
        try {
            $mock->process($commandData);
        } catch (ProcessorErrorException $e) {
            $this->assertEquals($command, $e->getCommand());
            $this->assertEquals($processor, $e->getProcessor());
            $this->assertEquals($exception, $e->getPrevious());
            $this->assertEquals($exception->getMessage(), $e->getMessage());
            $this->assertEquals($exception->getCode(), $e->getCode());
            throw $e;
        }
    }    
}