<?php

namespace Gendoria\CommandQueue\Console\Command;

use Gendoria\CommandQueue\Worker\WorkerRunnerManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of RunWorkerCommand
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class ListWorkersCommand extends Command
{
    /**
     * Worker runner manager.
     * 
     * @var WorkerRunnerManager
     */
    private $runnerManager;
    
    /**
     * Set worker runner manager.
     * 
     * @param WorkerRunnerManager $runnerManager Worker runner manager instance.
     */
    public function setRunnerManager(WorkerRunnerManager $runnerManager)
    {
        $this->runnerManager = $runnerManager;
    }    

    protected function configure()
    {
        $this->setName('cmq:worker:list')
            ->setDescription('List available workers');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (null === $this->runnerManager) {
            $output->writeln("<error>Runner manager not provided to command. Command is not correctly initialized.</error>");
            return 1;
        }
        $runners = $this->runnerManager->getRunners();
        $runnersFormatted = array_map(array($this, 'formatRunnerName'), $runners);
        $output->writeln('Registered workers:');
        $output->writeln($runnersFormatted);
    }

    public function formatRunnerName($name)
    {
        return sprintf("  * <info>%s</info>", $name);
    }    
}
