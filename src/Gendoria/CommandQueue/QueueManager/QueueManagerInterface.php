<?php

namespace Gendoria\CommandQueue\QueueManager;

use Gendoria\CommandQueue\SendDriver\SendDriverInterface;

/**
 * Interface describing operations of command queue manager.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
interface QueueManagerInterface extends QueueSenderInterface
{
    /**
     * Set send driver for command queue.
     *
     * @param SendDriverInterface $sendDriver
     */
    public function setSendDriver(SendDriverInterface $sendDriver);
}
