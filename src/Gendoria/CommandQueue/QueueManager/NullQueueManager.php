<?php

namespace Gendoria\CommandQueue\QueueManager;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\SendDriver\SendDriverInterface;

/**
 * Description of NullQueueManager
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class NullQueueManager implements MultipleQueueManagerInterface, CommandRouterInterface, SingleQueueManagerInterface
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

    /**
     * {@inheritdoc}
     */
    public function setSendDriver(SendDriverInterface $sendDriver)
    {
    }
}
