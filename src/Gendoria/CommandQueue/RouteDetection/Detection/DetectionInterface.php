<?php

namespace Gendoria\CommandQueue\RouteDetection\Detection;

/**
 * Interface for pool detection.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface DetectionInterface
{
    /**
     * Get the pool name.
     *
     * @return string
     */
    public function getPoolName();
}
