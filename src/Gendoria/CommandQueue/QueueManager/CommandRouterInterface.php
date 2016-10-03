<?php

namespace Gendoria\CommandQueue\QueueManager;

/**
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface CommandRouterInterface
{
    /**
     * Add new command route.
     *
     * @param string $commandExpression
     * @param string $poolName
     * @return void
     */
    public function addCommandRoute($commandExpression, $poolName);
}
