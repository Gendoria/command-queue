<?php

namespace Gendoria\CommandQueue\CommandProcessor;

use Gendoria\CommandQueue\Command\CommandInterface;
use Psr\Log\LoggerInterface;

/**
 * Interface for Command Processor.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface CommandProcessorInterface
{
    /**
     * Process command.
     *
     * @param CommandInterface $command
     * @return void
     * @throws \Exception Thrown, when processing returned with an error.
     */
    public function process(CommandInterface $command);
    
    /**
     * Return true, if processor supports given command, false otherwise.
     *
     * @param CommandInterface $command
     * @return boolean True, if processor supports given command.
     */
    public function supports(CommandInterface $command);

    /**
     * Set Logger instance.
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger);
}
