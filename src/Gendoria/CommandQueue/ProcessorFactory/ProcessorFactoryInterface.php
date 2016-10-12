<?php

namespace Gendoria\CommandQueue\ProcessorFactory;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;
use Gendoria\CommandQueue\ProcessorFactory\Exception\MultipleProcessorsException;
use Gendoria\CommandQueue\ProcessorFactory\Exception\ProcessorNotFoundException;
use InvalidArgumentException;

/**
 * Interface describing operations of command processor factory.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface ProcessorFactoryInterface
{
    /**
     * Register processor for given command class.
     *
     * @param string                    $commandClassName
     * @param CommandProcessorInterface $service
     * @return void
     *
     * @throws InvalidArgumentException Thrown, when given command class does not exist, or does not implement CommandInterface.
     * @throws MultipleProcessorsException Thrown, when multiple processors are registered on same command classes.
     *
     * @see CommandInterface
     */
    public function registerProcessorForCommand($commandClassName, CommandProcessorInterface $service);

    /**
     * Return command processor for a given command.
     *
     * @param CommandInterface $command
     *
     * @return CommandProcessorInterface
     *
     * @throws ProcessorNotFoundException
     */
    public function getProcessor(CommandInterface $command);
    
    /**
     * Return true, if processor for given class is already registered.
     *
     * @param string $commandClassName
     * @return boolean True,if processor for given class is already registered.
     */
    public function hasProcessor($commandClassName);
}
