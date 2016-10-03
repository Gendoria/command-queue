<?php

/*
 * All rights reserved
 * Copyright 2016 Isobar Poland
 */

namespace Gendoria\CommandQueue\QueueManager;

use Gendoria\CommandQueue\Command\CommandInterface;
use RuntimeException;

/**
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
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
