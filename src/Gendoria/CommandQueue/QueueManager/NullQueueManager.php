<?php

/*
 * All rights reserved
 * Copyright 2016 Isobar Poland
 */

namespace Gendoria\CommandQueue\QueueManager;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\SendDriver\SendDriverInterface;

/**
 * Description of NullQueueManager
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
class NullQueueManager implements MultipleQueueManagerInterface, CommandRouterInterface
{
    /**
     * {@inheritdoc}
     */
    public function addSendDriver($pool, SendDriverInterface $sendDriver, $isDefault = false)
    {
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function addCommandRoute($commandExpression, $poolName)
    {
    }
    
    /**
     * {@inheritdoc}
     */
    public function sendCommand(CommandInterface $command)
    {
        
    }
}
