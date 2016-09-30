<?php

namespace Gendoria\CommandQueue;

use Gendoria\CommandQueue\Command\CommandInterface;
use Psr\Log\LoggerInterface;

/**
 * Interface for Command Processor.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
interface CommandProcessorInterface
{
    /**
     * Process command.
     *
     * @param CommandInterface $command
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
     */
    public function setLogger(LoggerInterface $logger);
}
