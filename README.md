# Command queue library

This library delivers basic interfaces and tools to create command backend delegation system, which allows
passing tasks for processing by scallable backend worker infrastructure.

[![Build Status](https://img.shields.io/travis/Gendoria/command-queue/master.svg)](https://travis-ci.org/Gendoria/command-queue)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/Gendoria/command-queue.svg)](https://scrutinizer-ci.com/g/Gendoria/command-queue/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Gendoria/command-queue.svg)](https://scrutinizer-ci.com/g/Gendoria/command-queue/?branch=master)
[![Downloads](https://img.shields.io/packagist/dt/gendoria/command-queue.svg)](https://packagist.org/packages/gendoria/command-queue)
[![Latest Stable Version](https://img.shields.io/packagist/v/gendoria/command-queue.svg)](https://packagist.org/packages/gendoria/command-queue)

Library created in cooperation with [Isobar Poland](http://www.isobar.com/pl/).

![Isobar Poland](doc/images/isobar.jpg "Isobar Poland logo") 

## Installation

### Step 1: Download the library


Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require gendoria/command-queue "dev-master"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

## Usage

This library provides building blocks to create command queue system, where you delgate
your tasks to a pools of backend workers for further processing.

By itself, it does have a simple implementation of 'direct' command queue,
where commands are not sent, but executed locally by a driver.

The simplest example of working queue chain using direct processing driver is below.

```php
use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\CommandProcessor\CommandProcessorInterface;
use Gendoria\CommandQueue\ProcessorFactory\ProcessorFactory;
use Gendoria\CommandQueue\SendDriver\DirectProcessingDriver;
use Psr\Log\LoggerInterface;

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

$driver = new DirectProcessingDriver();
$driver->setProcessorFactory($processorFactory);

for ($k = 0; $k < 5; $k++) {
    $command = new SimpleCommand("Test ".($k+1));
    $driver->send($command);
    sleep(1);
}
```

You can find this example in `examples/direct.php` file. If you run it, you should see the following output:

```console
$ php example/direct.php
Command class: SimpleCommand
Command payload: Test 1

Command class: SimpleCommand
Command payload: Test 2

Command class: SimpleCommand
Command payload: Test 3

Command class: SimpleCommand
Command payload: Test 4

Command class: SimpleCommand
Command payload: Test 5

```

Direct processing driver is not very usable in real world application, as it does not delegate any tasks.
But you can use it at an early stage of your application development, when you know, for tasks you want
to delegate to backend processing at later date.