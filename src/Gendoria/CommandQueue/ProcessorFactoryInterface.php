<?php

namespace Gendoria\CommandQueue;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;
use InvalidArgumentException;

/**
 * Interface describing operations of command processor factory.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
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
     * @throws Exception\MultipleProcessorsException Thrown, when multiple processors are registered on same command classes.
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
     * @throws ProcessorNotFoundExceptions
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
