<?php

namespace Gendoria\CommandQueue\Worker\Exception;

use Exception;

/**
 * Exception thrown, when command translation has been unsuccessfull.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
class TranslateErrorException extends Exception
{
    /**
     * Command data.
     * 
     * @var mixed
     */
    private $commandData;
    
    /**
     * Class constructor.
     * 
     * @param mixed $commandData
     * @param string $message
     * @param integer $code
     * @param Exception $previous
     */
    public function __construct($commandData, $message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->commandData = $commandData;
    }
    
    /**
     * Get command data.
     * 
     * @return mixed
     */
    function getCommandData()
    {
        return $this->commandData;
    }
}
