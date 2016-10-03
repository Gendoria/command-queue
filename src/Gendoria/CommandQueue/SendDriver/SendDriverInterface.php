<?php

namespace Gendoria\CommandQueue\SendDriver;

use Gendoria\CommandQueue\Command\CommandInterface;

/**
 * Interface describing operations for queue send driver.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface SendDriverInterface
{
    /**
     * Send command for processing.
     *
     * @param CommandInterface $command
     * @return void
     * @throws \Exception Thrown, when sending resulted in error.
     */
    public function send(CommandInterface $command);
}
