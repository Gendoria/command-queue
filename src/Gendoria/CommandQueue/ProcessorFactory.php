<?php

namespace Gendoria\CommandQueue;

use InvalidArgumentException;
use Gendoria\CommandQueue\Command\CommandInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionClass;

/**
 * Command Processor factory - used for registering and retrieving command processor.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
class ProcessorFactory implements ProcessorFactoryInterface
{
    /**
     * Array of registered command processors.
     *
     * @var CommandProcessorInterface[]
     */
    private $commandTypes = array();

    /**
     * Logger instance.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Class constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ? $logger : new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function registerProcessorForCommand($commandClassName, CommandProcessorInterface $service)
    {
        if (!class_exists($commandClassName)) {
            throw new InvalidArgumentException('Registering service for non existing command class.');
        }
        $classData = new ReflectionClass($commandClassName);
        if (!$classData->implementsInterface('Gendoria\CommandQueue\Command\CommandInterface')) {
            throw new InvalidArgumentException('Command class has to implement CommandInterface interface.');
        }
        if (array_key_exists($commandClassName, $this->commandTypes)) {
            throw new Exception\MultipleProcessorsException($commandClassName);
        }
        $this->commandTypes[$commandClassName] = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessor(CommandInterface $command)
    {
        if (!array_key_exists(get_class($command), $this->commandTypes) || !$this->commandTypes[get_class($command)]->supports($command)) {
            throw new ProcessorNotFoundException('No processor registered for given command class: '.get_class($command));
        }

        return $this->commandTypes[get_class($command)];
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasProcessor($commandClassName)
    {
        return array_key_exists($commandClassName, $this->commandTypes);
    }
}
