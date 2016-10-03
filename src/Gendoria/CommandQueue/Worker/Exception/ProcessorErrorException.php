<?php

namespace Gendoria\CommandQueue\Worker\Exception;

use Exception;
use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;

/**
 * Exception thrown, when command translation has been unsuccessfull.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
class ProcessorErrorException extends Exception
{
    /**
     * Command data.
     * 
     * @var CommandInterface
     */
    private $command;
    
    /**
     * Command processor.
     * 
     * @var CommandProcessorInterface
     */
    private $processor;
    
    /**
     * Class constructor.
     * 
     * @param CommandInterface $command
     * @param CommandProcessorInterface $processor
     * @param string $message
     * @param integer $code
     * @param Exception $previous
     */
    public function __construct(CommandInterface $command, CommandProcessorInterface $processor, $message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->command = $command;
        $this->processor = $processor;
    }
    
    /**
     * Get command.
     * 
     * @return CommandInterface
     */
    function getCommand()
    {
        return $this->command;
    }

    /**
     * Get command processor.
     * 
     * @return CommandProcessorInterface
     */
    function getProcessor()
    {
        return $this->processor;
    }
}
