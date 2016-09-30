<?php

/*
 * All rights reserved
 * Copyright 2016 Isobar Poland
 */

namespace Gendoria\CommandQueue\RouteDetection;

/**
 * Interface for pool detection.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
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
