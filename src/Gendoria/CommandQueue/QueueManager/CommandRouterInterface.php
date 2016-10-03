<?php

/*
 * All rights reserved
 * Copyright 2016 Isobar Poland
 */

namespace Gendoria\CommandQueue\QueueManager;

/**
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
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
