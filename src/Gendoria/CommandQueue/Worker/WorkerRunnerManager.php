<?php

namespace Gendoria\CommandQueue\Worker;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Worker runner manager has capabilities of managing workers.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class WorkerRunnerManager
{
    /**
     * Worker runner services configuration.
     * 
     * @var array
     */
    private $runners = array();
    
    /**
     * Register runner.
     * 
     * @param string $name Worker name.
     * @param WorkerRunnerInterface $runner Worker runner.
     * @param array $options Worker options.
     */
    public function addRunner($name, WorkerRunnerInterface $runner, array $options = array())
    {
        $this->runners[$name] = array(
            'runner' => $runner,
            'options' => $options,
        );
    }
    
    /**
     * Return true, if worker with given name is registered, false otherwise.
     * 
     * @param string $name
     * @return boolean
     */
    public function has($name)
    {
        return array_key_exists($name, $this->runners);
    }
    
    /**
     * Run worker.
     * 
     * @param string $name
     * @param OutputInterface $output
     * @throws InvalidArgumentException Thrown, if worker cannto be found for provided name.
     * @throws Exception Can be thrown, if runner resulted with an error.
     */
    public function run($name, OutputInterface $output = null)
    {
        if (!$this->has($name)) {
            throw new InvalidArgumentException("No runner service registered for provided name.");
        }
        /* @var $runner WorkerRunnerInterface */
        $runner = $this->runners[$name]['runner'];
        $runner->run($this->runners[$name]['options'], $output);
    }
    
    /**
     * Get registered runners.
     * 
     * @return string[]
     */
    public function getRunners()
    {
        return array_keys($this->runners);
    }

}
