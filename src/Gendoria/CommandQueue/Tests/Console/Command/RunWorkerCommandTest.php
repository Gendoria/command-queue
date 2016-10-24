<?php

namespace Gendoria\CommandQueue\Tests\Console\Command;

use Gendoria\CommandQueue\Console\Command\RunWorkerCommand;
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
class RunWorkerCommandTest extends PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $manager = new WorkerRunnerManager();
        $runner = $this->getMockBuilder(WorkerRunnerInterface::class)->getMock();
        $manager->addRunner('test', $runner);
        
        $application = new Application();
        $testedCommand = new RunWorkerCommand();
        $testedCommand->setRunnerManager($manager);
        $application->add($testedCommand);

        $command = $application->find('cmq:worker:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'name'    => 'test',
            )
        );
    }
    
    public function testExecuteNoWorker()
    {
        $manager = new WorkerRunnerManager();
        $runner = $this->getMockBuilder(WorkerRunnerInterface::class)->getMock();
        $manager->addRunner('different', $runner);
        
        $application = new Application();
        $testedCommand = new RunWorkerCommand();
        $testedCommand->setRunnerManager($manager);
        $application->add($testedCommand);

        $command = $application->find('cmq:worker:run');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute(
            array(
                'name'    => 'test',
            )
        );
        $this->assertEquals(1, $exitCode);
        $this->assertContains('Worker "test" not registered.', $commandTester->getDisplay());
        $this->assertContains('different', $commandTester->getDisplay());
    }  
    
    public function testExecuteNoManager()
    {
        $application = new Application();
        $testedCommand = new RunWorkerCommand();
        $application->add($testedCommand);

        $command = $application->find('cmq:worker:run');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute(
            array(
                'name'    => 'test',
            )
        );
        $this->assertEquals(1, $exitCode);
        $this->assertContains('Runner manager not provided to command. Command is not correctly initialized.', $commandTester->getDisplay());
    }    
}
