<?php

namespace Gendoria\CommandQueue\Worker;

use Exception;
use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;
use Gendoria\CommandQueue\ProcessorFactoryInterface;
use Gendoria\CommandQueue\ProcessorNotFoundException;
use Gendoria\CommandQueue\Serializer\SerializedCommandData;
use Gendoria\CommandQueue\Serializer\SerializerInterface;
use Gendoria\CommandQueue\Worker\Exception\ProcessorErrorException;
use Gendoria\CommandQueue\Worker\Exception\TranslateErrorException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Base command queue worker
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
abstract class BaseWorker implements WorkerInterface
{

    /**
     * Processor factory instance.
     *
     * @var ProcessorFactoryInterface
     */
    protected $processorFactory;

    /**
     * Logger instance.
     *
     * @var LoggerInterface
     */
    protected $logger;
    
    /**
     * Serializer.
     * 
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Class constructor.
     *
     * @param ProcessorFactoryInterface         $processorFactory
     * @param LoggerInterface          $logger             Logger instance.
     */
    public function __construct(ProcessorFactoryInterface $processorFactory, SerializerInterface $serializer, LoggerInterface $logger = null)
    {
        $this->processorFactory = $processorFactory;
        $this->serializer = $serializer;
        $this->logger = $logger ? $logger : new NullLogger();
    }

    /**
     * 
     * @param mixed $commandData
     * @return void
     * 
     * @throws TranslateErrorException Thrown, when translation process resulted in an error.
     * @throws ProcessorNotFoundException Thrown, when processor for given command has not been found.
     * @throws ProcessorErrorException Thrown, when processor resulted in an error.
     */
    public function process($commandData)
    {
        $this->beforeTranslateHook($commandData);
        $command = $this->serializer->unserialize($this->getSerializedCommandData($commandData));
        $this->beforeGetProcessorHook($command);
        $processor = $this->getProcessor($command);
        $this->beforeProcessHook($command, $processor);
        try {
            $processor->process($command);
        } catch (Exception $e) {
            $this->processorErrorHook($command, $processor, $e);
            throw new ProcessorErrorException($command, $processor, $e->getMessage(), $e->getCode(), $e);
        }
        $this->afterProcessHook($command, $processor);
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessor(CommandInterface $command)
    {
        $processor = $this->processorFactory->getProcessor($command);
        $processor->setLogger($this->logger);
        return $processor;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setProcessorFactory(ProcessorFactoryInterface $processorFactory)
    {
        $this->processorFactory = $processorFactory;
    }
    
    /**
     * Get serialized command data to use with translator.
     * 
     * @param mixed $commandData
     * @return SerializedCommandData
     * 
     * @throws TranslateErrorException Thrown, when data could not have been translated to serialized command data.
     */
    abstract protected function getSerializedCommandData($commandData);
    
    /**
     * Hook called before command translation.
     * 
     * @param string $commandData
     * @return void
     * 
     * @codeCoverageIgnore
     */
    protected function beforeTranslateHook(&$commandData)
    {
    }
    
    /**
     * Hook called before getting processor for command.
     * 
     * @param CommandInterface $command
     * @return void
     * 
     * @codeCoverageIgnore
     */
    protected function beforeGetProcessorHook(CommandInterface $command)
    {
    }

    /**
     * Hook called before processing command.
     * 
     * @param CommandInterface $command
     * @return void
     * 
     * @codeCoverageIgnore
     */
    protected function beforeProcessHook(CommandInterface $command, CommandProcessorInterface $processor)
    {
    }
    
    /**
     * Hook called after successfull processing of command.
     * 
     * @param CommandInterface $command
     * @param CommandProcessorInterface $processor
     * @return void
     * 
     * @codeCoverageIgnore
     */
    protected function afterProcessHook(CommandInterface $command, CommandProcessorInterface $processor)
    {
    }
    
    /**
     * Hook called after successfull processing of command.
     * 
     * @param CommandInterface $command
     * @param CommandProcessorInterface $processor
     * @param Exception $e Exception thrown by processor.
     * @return void
     * 
     * @codeCoverageIgnore
     */
    protected function processorErrorHook(CommandInterface $command, CommandProcessorInterface $processor, Exception $e)
    {
    }    
}
