<?php

/*
 * All rights reserved
 * Copyright 2016 Isobar Poland
 */

namespace Gendoria\CommandQueue\QueueManager;

use Gendoria\CommandQueue\SendDriver\SendDriverInterface;

/**
 * This interface describes service, which can send the command to different pools,
 * based on some internal configuration.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
interface MultipleQueueManagerInterface extends QueueSenderInterface
{
    /**
     * Set send driver for command queue.
     *
     * @param string              $pool       Send driver pool name.
     * @param SendDriverInterface $sendDriver
     * @param boolean             $isDefault  If true, added pool is marked as default.
     */
    public function addSendDriver($pool, SendDriverInterface $sendDriver, $isDefault = false);
}
