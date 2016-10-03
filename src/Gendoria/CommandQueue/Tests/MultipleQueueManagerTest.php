<?php

namespace Gendoria\CommandQueue\Tests;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\QueueManager\MultipleQueueManager;
use Gendoria\CommandQueue\SendDriver\SendDriverInterface;
use Gendoria\CommandQueue\Tests\Fixtures\DummyChildCommand;
use Gendoria\CommandQueue\Tests\Fixtures\DummyCommand;
use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * Description of MultipleQueueManagerTest
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 * @group CommandQueue
 */
class MultipleQueueManagerTest extends PHPUnit_Framework_TestCase
{
    public function testNoPool()
    {
        $this->setExpectedException(RuntimeException::class);
        $manager = new MultipleQueueManager();
        $manager->sendCommand($this->getMockBuilder(CommandInterface::class)->getMock());
    }
    
    public function testDefaultPool()
    {
        $command = new DummyCommand();
        $sendDriver = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver->expects($this->once())
            ->method('send')
            ->with($command);
        $manager = new MultipleQueueManager();
        $manager->addSendDriver('default', $sendDriver, true);
        $manager->sendCommand($command);
    }
    
    public function testSimpleCommandRoute()
    {
        $command = new DummyCommand();
        $sendDriver1 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver1->expects($this->never())
            ->method('send');
        $sendDriver2 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver2->expects($this->once())
            ->method('send')
            ->with($command);
        $manager = new MultipleQueueManager();
        $manager->addSendDriver('default', $sendDriver1, true);
        $manager->addSendDriver('manager2', $sendDriver2);
        $manager->addCommandRoute(get_class($command), 'manager2');
        $manager->sendCommand($command);
    }
    
    public function testRegexpCommandRoute()
    {
        $command = new DummyCommand();
        $sendDriver1 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver1->expects($this->never())
            ->method('send');
        $sendDriver2 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver2->expects($this->once())
            ->method('send')
            ->with($command);
        $manager = new MultipleQueueManager();
        $manager->addSendDriver('default', $sendDriver1, true);
        $manager->addSendDriver('manager2', $sendDriver2);
        $manager->addCommandRoute('*'.substr(get_class($command), 5).'*', 'manager2');
        $manager->sendCommand($command);
    }    
    
    public function testBaseClassCommandRoute()
    {
        $command = new DummyChildCommand();
        $sendDriver1 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver1->expects($this->never())
            ->method('send')
            ;
        $sendDriver2 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver2->expects($this->once())
            ->method('send')
            ->with($command);
        $manager = new MultipleQueueManager();
        $manager->addSendDriver('default', $sendDriver1, true);
        $manager->addSendDriver('manager2', $sendDriver2);
        $manager->addCommandRoute(DummyCommand::class, 'manager2');
        $manager->sendCommand($command);
    }    
    
    public function testInterfaceCommandRoute()
    {
        $command = new DummyCommand();
        $sendDriver1 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver1->expects($this->never())
            ->method('send')
            ;
        $sendDriver2 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver2->expects($this->once())
            ->method('send')
            ->with($command);
        $manager = new MultipleQueueManager();
        $manager->addSendDriver('default', $sendDriver1, true);
        $manager->addSendDriver('manager2', $sendDriver2);
        $manager->addCommandRoute(CommandInterface::class, 'manager2');
        $manager->sendCommand($command);
    }
    
    public function testBaseClassPrecedenceOverInterface()
    {
        $command = new DummyChildCommand();
        $sendDriver1 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver1->expects($this->never())
            ->method('send')
            ;
        $sendDriver2 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver2->expects($this->once())
            ->method('send')
            ->with($command);
        $sendDriver3 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver3->expects($this->never())
            ->method('send')
            ;
        $manager = new MultipleQueueManager();
        $manager->addSendDriver('manager1', $sendDriver1, true);
        $manager->addSendDriver('manager2', $sendDriver2);
        $manager->addSendDriver('manager3', $sendDriver3);
        $manager->addCommandRoute(DummyCommand::class, 'manager2');
        $manager->addCommandRoute(CommandInterface::class, 'manager3');
        $manager->sendCommand($command);
    }
    
    public function testClassInterfacePrecedenceOverBaseClassInterface()
    {
        $command = new DummyChildCommand();
        $sendDriver1 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver1->expects($this->never())
            ->method('send')
            ;
        $sendDriver2 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver2->expects($this->once())
            ->method('send')
            ->with($command);
        $sendDriver3 = $this->getMockBuilder(SendDriverInterface::class)->getMock();
        $sendDriver3->expects($this->never())
            ->method('send')
            ;
        $manager = new MultipleQueueManager();
        $manager->addSendDriver('manager1', $sendDriver1, true);
        $manager->addSendDriver('manager2', $sendDriver2);
        $manager->addSendDriver('manager3', $sendDriver3);
        $manager->addCommandRoute(Fixtures\DummyChildInterface::class, 'manager2');
        $manager->addCommandRoute(Fixtures\DummyInterface::class, 'manager3');
        $manager->sendCommand($command);
    }    
}
