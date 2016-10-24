<?php

namespace Gendoria\CommandQueue\Worker;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Worker runner interface describes functions needed to register service as worker runner.
 * 
 * Worker runner service can be run using one console command, independent of the driver used.
 * 
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface WorkerRunnerInterface
{
    /**
     * Run worker with provided options.
     * 
     * @param array $options
     * @param OutputInterface $output
     * @return void
     * 
     * @throws InvalidArgumentException Thrown, if options array is incorrect for this worker.
     * @throws Exception Thrown, if worker could not be run or resulted in error.
     */
    public function run(array $options, OutputInterface $output = null);
}
