<?php

namespace Gendoria\CommandQueue\Worker;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;
use Gendoria\CommandQueue\ProcessorFactoryInterface;
use Gendoria\CommandQueue\ProcessorNotFoundException;

/**
 * This interface describes functionality of a single processor worker node.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface WorkerInterface
{
    /**
     * Process single command.
     *
     * @param CommandInterface $command
     *
     * @throws ProcessorNotFoundException
     *
     * @return CommandProcessorInterface Processor used to process command.
     */
    public function process(CommandInterface $command);

    /**
     * Set processor factory.
     *
     * @param ProcessorFactoryInterface $processorFactory
     * @return void
     */
    public function setProcessorFactory(ProcessorFactoryInterface $processorFactory);
}
