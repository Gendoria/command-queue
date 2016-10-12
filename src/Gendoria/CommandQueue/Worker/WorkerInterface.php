<?php

namespace Gendoria\CommandQueue\Worker;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;
use Gendoria\CommandQueue\ProcessorFactory\Exception\ProcessorNotFoundException;
use Gendoria\CommandQueue\ProcessorFactory\ProcessorFactoryInterface;
use Gendoria\CommandQueue\Worker\Exception\ProcessorErrorException;
use Gendoria\CommandQueue\Worker\Exception\UnserializeErrorException;

/**
 * This interface describes functionality of a single processor worker node.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface WorkerInterface
{
    /**
     * Process single command data.
     * 
     * @param mixed $commandData Command data used by command transport layer.
     * @return void
     * 
     * @throws ProcessorErrorException Thrown, when processor returned error.
     * @throws ProcessorNotFoundException Thrown, when processor for given command has not been found.
     * @throws UnserializeErrorException Thrown, when command data could not have been translated to command.
     */
    public function process($commandData);
    
    /**
     * Return processor for given command.
     * 
     * @return CommandProcessorInterface Processor, that can be used to process command.
     * 
     * @throws ProcessorNotFoundException Thrown, when processor has not been found.
     */
    public function getProcessor(CommandInterface $command);

    /**
     * Set processor factory.
     *
     * @param ProcessorFactoryInterface $processorFactory
     * @return void
     */
    public function setProcessorFactory(ProcessorFactoryInterface $processorFactory);
}
