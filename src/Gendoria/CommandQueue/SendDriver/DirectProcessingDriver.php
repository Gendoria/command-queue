<?php

namespace Gendoria\CommandQueue\SendDriver;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\ProcessorFactoryInterface;
use Gendoria\CommandQueue\ProcessorNotFoundException;
use Gendoria\CommandQueue\Worker\WorkerInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Description of DirectProcessingDriver.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class DirectProcessingDriver implements SendDriverInterface, WorkerInterface, LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;
    
    /**
     * Processor factory.
     *
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * {@inheritdoc}
     */
    public function send(CommandInterface $command)
    {
        try {
            $this->process($command);
        } catch (ProcessorNotFoundException $e) {
            if ($this->logger) {
                $this->logger->error('Exception while sending command: '.$e->getMessage());
            }
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error('Exception while sending command: '.$e->getMessage());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function process(CommandInterface $command)
    {
        $processor = $this->processorFactory->getProcessor($command);
        $processor->process($command);

        return $processor;
    }

    /**
     * {@inheritdoc}
     */
    public function setProcessorFactory(ProcessorFactoryInterface $processorFactory)
    {
        $this->processorFactory = $processorFactory;
    }
}
