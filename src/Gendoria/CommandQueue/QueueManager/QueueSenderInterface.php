<?php

namespace Gendoria\CommandQueue\QueueManager;

use Gendoria\CommandQueue\Command\CommandInterface;
use RuntimeException;

/**
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface QueueSenderInterface
{
    /**
     * Send command to command queue.
     *
     * @param CommandInterface $command
     * @return void
     *
     * @throws RuntimeException Thrown, when send driver is not yet set.
     */
    public function sendCommand(CommandInterface $command);
}
