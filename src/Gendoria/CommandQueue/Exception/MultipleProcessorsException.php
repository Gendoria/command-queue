<?php

/**
 * All rights reserved
 * Copyright 2016 Isobar Poland
 */

namespace Gendoria\CommandQueue\Exception;

use Exception;

/**
 * Exception thrown, when multiple processors are registered for given command class.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
class MultipleProcessorsException extends Exception
{
    /**
     * Command class.
     *
     * @var string
     */
    private $commandClassName;
    
    /**
     * Class constructor.
     *
     * @param string    $commandClassName
     * @param Exception $previous
     */
    public function __construct($commandClassName, Exception $previous = null)
    {
        $this->commandClassName = $commandClassName;
        parent::__construct("Multiple processors registered for command class ".$commandClassName, 500, $previous);
    }
    
    /**
     * Get command class name.
     *
     * @return string
     */
    public function getCommandClassName()
    {
        return $this->commandClassName;
    }
}
