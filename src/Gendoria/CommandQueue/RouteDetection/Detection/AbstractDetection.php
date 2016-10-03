<?php

namespace Gendoria\CommandQueue\RouteDetection\Detection;

/**
 * Description of AbstractDetection.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class AbstractDetection implements DetectionInterface
{
    /**
     * Pool name.
     *
     * @var string
     */
    private $poolName;

    /**
     * Class constructor.
     *
     * @param string $poolName
     */
    public function __construct($poolName)
    {
        $this->poolName = $poolName;
    }

    /**
     * Get pool name.
     *
     * @return string
     */
    public function getPoolName()
    {
        return $this->poolName;
    }
}
