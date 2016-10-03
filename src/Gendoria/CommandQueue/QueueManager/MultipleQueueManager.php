<?php

namespace Gendoria\CommandQueue\QueueManager;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\RouteDetection\Detection\DetectionInterface;
use Gendoria\CommandQueue\RouteDetection\Detector\CachedRouteDetector;
use Gendoria\CommandQueue\SendDriver\SendDriverInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;

/**
 * Description of MultipleQueueManager
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class MultipleQueueManager implements MultipleQueueManagerInterface, LoggerAwareInterface, CommandRouterInterface
{

    /**
     * Send drivers array, where keays are pool names.
     *
     * @var SendDriverInterface[]
     */
    private $sendDrivers;

    /**
     * Default pool name.
     *
     * @var string
     */
    private $defaultPool = "";

    /**
     * Logger interface.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Pool detector.
     *
     * @var CachedRouteDetector
     */
    private $poolDetector;

    public function __construct(LoggerInterface $logger = null)
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new NullLogger();
        }
        $this->poolDetector = new CachedRouteDetector();
    }

    /**
     * Add new command route.
     *
     * @param string $commandExpression
     * @param string $poolName
     */
    public function addCommandRoute($commandExpression, $poolName)
    {
        $this->poolDetector->addRoute($commandExpression, $poolName);
    }

    /**
     * {@inheritdoc}
     */
    public function addSendDriver(
    $pool, SendDriverInterface $sendDriver, $isDefault = false
    )
    {
        $this->sendDrivers[$pool] = $sendDriver;
        if (!$this->defaultPool || $isDefault) {
            $this->defaultPool = $pool;
            $this->poolDetector->setDefault($pool);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendCommand(CommandInterface $command)
    {
        $poolName = $this->detectPool($command);
        if (!$poolName || empty($this->sendDrivers[$poolName])) {
            $this->logger->critical("Send driver not set for given command class, or no default send driver set.",
                array($command, $this));
            throw new RuntimeException("Send driver not set");
        }
        $this->logger->debug(sprintf("Sending command class %s to pool %s",
                get_class($command), $poolName), array($command, $this));
        $this->sendDrivers[$poolName]->send($command);
    }

    /**
     * Set logger instance.
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Detect correct pool for given command.
     *
     * @param CommandInterface $command
     * @return string
     */
    private function detectPool(CommandInterface $command)
    {
        $commandClass = get_class($command);
        return $this->poolDetector->detect($commandClass);
    }

}
