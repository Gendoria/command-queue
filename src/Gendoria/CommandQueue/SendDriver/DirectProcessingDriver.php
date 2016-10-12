<?php

namespace Gendoria\CommandQueue\SendDriver;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\ProcessorFactory\ProcessorFactoryInterface;
use Gendoria\CommandQueue\ProcessorFactory\ProcessorNotFoundException;
use Gendoria\CommandQueue\Worker\Exception\ProcessorErrorException;
use Gendoria\CommandQueue\Worker\Exception\TranslateErrorException;
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
    public function process($command)
    {
        if (!$command instanceof CommandInterface) {
            throw new TranslateErrorException($command, "Command has to be instance of command interface for direct processing driver", 500);
        }
        $processor = $this->getProcessor($command);
        try {
            $processor->process($command);
        } catch (\Exception $e) {
            throw new ProcessorErrorException($command, $processor, $e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getProcessor(CommandInterface $command)
    {
        $processor = $this->processorFactory->getProcessor($command);
        if ($this->logger) {
            $processor->setLogger($this->logger);
        }
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
