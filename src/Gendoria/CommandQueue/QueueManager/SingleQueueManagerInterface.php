<?php

namespace Gendoria\CommandQueue\QueueManager;

use Gendoria\CommandQueue\SendDriver\SendDriverInterface;

/**
 * Interface describing operations of command queue manager.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface SingleQueueManagerInterface extends QueueSenderInterface
{
    /**
     * Set send driver for command queue.
     *
     * @param SendDriverInterface $sendDriver
     * @return void
     */
    public function setSendDriver(SendDriverInterface $sendDriver);
}
