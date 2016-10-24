<?php

namespace Gendoria\CommandQueue\Tests\Console\Command;

use Gendoria\CommandQueue\Console\Command\ListWorkersCommand;
use Gendoria\CommandQueue\Worker\WorkerRunnerInterface;
use Gendoria\CommandQueue\Worker\WorkerRunnerManager;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test run worker command.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class ListWorkersCommandTest extends PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $manager = new WorkerRunnerManager();
        $runner = $this->getMockBuilder(WorkerRunnerInterface::class)->getMock();
        $manager->addRunner('different', $runner);
        
        $application = new Application();
        $testedCommand = new ListWorkersCommand();
        $testedCommand->setRunnerManager($manager);
        $application->add($testedCommand);

        $command = $application->find('cmq:worker:list');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute(array());
        $this->assertEquals(0, $exitCode);
        $this->assertContains('different', $commandTester->getDisplay());
    }

    public function testExecuteNoManager()
    {
        $application = new Application();
        $testedCommand = new ListWorkersCommand();
        $application->add($testedCommand);

        $command = $application->find('cmq:worker:list');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute(array());
        $this->assertEquals(1, $exitCode);
        $this->assertContains('Runner manager not provided to command. Command is not correctly initialized.', $commandTester->getDisplay());
    }        
}
