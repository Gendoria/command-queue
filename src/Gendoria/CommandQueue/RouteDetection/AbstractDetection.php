<?php

/**
 * All rights reserved
 * Copyright 2016 Isobar Poland.
 */

namespace Gendoria\CommandQueue\RouteDetection;

/**
 * Description of AbstractDetection.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
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
