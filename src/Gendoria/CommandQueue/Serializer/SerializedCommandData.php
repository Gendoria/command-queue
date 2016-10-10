<?php

namespace Gendoria\CommandQueue\Serializer;

/**
 * Description of SerializedCommandData
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class SerializedCommandData
{
    /**
     * Serialized command representation. In most cases - a string
     * 
     * @var mixed
     */
    private $serializedCommand;
    
    /**
     * Command class name.
     * 
     * @var string
     */
    private $commandClass;
    
    /**
     * Class consructor.
     * 
     * @param mixed $serializedCommand
     * @param string $commandClass
     */
    public function __construct($serializedCommand, $commandClass)
    {
        $this->serializedCommand = $serializedCommand;
        $this->commandClass = $commandClass;
    }
    
    /**
     * Get serialized command representation.
     * 
     * @return mixed
     */
    public function getSerializedCommand()
    {
        return $this->serializedCommand;
    }

    /**
     * Get command class.
     * 
     * @return string
     */
    public function getCommandClass()
    {
        return $this->commandClass;
    }
}
