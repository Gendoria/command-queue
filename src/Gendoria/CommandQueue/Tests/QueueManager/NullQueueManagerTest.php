<?php

namespace Gendoria\CommandQueue\Tests\QueueManager;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\QueueManager\NullQueueManager;
use Gendoria\CommandQueue\SendDriver\SendDriverInterface;
use PHPUnit_Framework_TestCase;

/**
 * Description of MultipleQueueManagerTest
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 * @group CommandQueue
 */
class NullQueueManagerTest extends PHPUnit_Framework_TestCase
{
    public function testQm()
    {
        $qm = new NullQueueManager();
        $sendDriver = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $qm->addSendDriver('test', $sendDriver);
        $qm->addCommandRoute('.*', 'test');
        $this->assertNull($qm->sendCommand($command));
    }
}
