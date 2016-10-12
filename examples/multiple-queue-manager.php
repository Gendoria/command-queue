<?php

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;
use Gendoria\CommandQueue\ProcessorFactory\ProcessorFactory;
use Gendoria\CommandQueue\QueueManager\MultipleQueueManager;
use Gendoria\CommandQueue\SendDriver\DirectProcessingDriver;
use Psr\Log\LoggerInterface;

call_user_func(function() {
    $autoloadFile = __DIR__.'/../vendor/autoload.php';
    if (!is_file($autoloadFile)) {
        throw new LogicException('Could not find vendor/autoload.php. Did you forget to run "composer install --dev"?');
    }
    require $autoloadFile;
});

class SimpleCommand implements CommandInterface
{
    public $testData;
    
    public function __construct($testData)
    {
        $this->testData = $testData;
    }
}

class SimpleProcessor implements CommandProcessorInterface
{
    /**
     *
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * Process command.
     * 
     * @param SimpleCommand $command
     */
    public function process(CommandInterface $command)
    {
        echo "Command class: ".get_class($command)."\n";
        echo "Command payload: ".$command->testData."\n";
        echo "\n";
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function supports(CommandInterface $command)
    {
        return $command instanceof SimpleCommand;
    }

}

$simpleProcessor = new SimpleProcessor();

$processorFactory = new ProcessorFactory();
$processorFactory->registerProcessorForCommand(SimpleCommand::class, $simpleProcessor);

$driver = new DirectProcessingDriver($processorFactory);
$manager = new MultipleQueueManager();
$manager->addSendDriver('default', $driver, true);
$manager->addCommandRoute('SimpleCommand', 'default');

for ($k = 0; $k < 5; $k++) {
    $command = new SimpleCommand("Test ".($k+1));
    $manager->sendCommand($command);
    sleep(1);
}