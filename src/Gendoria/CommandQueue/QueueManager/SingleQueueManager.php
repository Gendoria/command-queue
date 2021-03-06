<?php

namespace Gendoria\CommandQueue\QueueManager;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\SendDriver\SendDriverInterface;
use RuntimeException;

/**
 * Simple queue manager.
 *
 */
class SingleQueueManager implements SingleQueueManagerInterface
{
    /**
     * Send driver.
     *
     * @var SendDriverInterface
     */
    private $sendDriver;

    /**
     * {@inheritdoc}
     */
    public function sendCommand(CommandInterface $command)
    {
        if (!$this->sendDriver) {
            throw new RuntimeException('Send driver not set');
        }
        $this->sendDriver->send($command);
    }

    /**
     * {@inheritdoc}
     */
    public function setSendDriver(SendDriverInterface $sendDriver)
    {
        $this->sendDriver = $sendDriver;
    }
}
