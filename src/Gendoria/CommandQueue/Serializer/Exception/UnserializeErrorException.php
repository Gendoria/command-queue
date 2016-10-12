<?php

namespace Gendoria\CommandQueue\Serializer\Exception;

use Exception;

/**
 * Exception thrown, when command translation has been unsuccessfull.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class UnserializeErrorException extends Exception
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
    public function getCommandData()
    {
        return $this->commandData;
    }
}
