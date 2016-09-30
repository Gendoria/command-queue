<?php

namespace Gendoria\CommandQueue\SendDriver;

use Gendoria\CommandQueue\Command\CommandInterface;

/**
 * Interface describing operations for queue send driver.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
interface SendDriverInterface
{
    /**
     * Send command for processing.
     *
     * @param CommandInterface $command
     */
    public function send(CommandInterface $command);
}
