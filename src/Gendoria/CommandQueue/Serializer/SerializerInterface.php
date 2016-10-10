<?php

namespace Gendoria\CommandQueue\Serializer;

use Gendoria\CommandQueue\Command\CommandInterface;

/**
 * Translator interface.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
interface SerializerInterface
{
    /**
     * Serialize command.
     * 
     * @param CommandInterface $command
     * @return SerializedCommandData
     */
    public function serialize(CommandInterface $command);
    
    /**
     * Unserialize command.
     * 
     * @param mixed $serializedCommandData
     * @param string $commandClass
     * @return CommandInterface
     */
    public function unserialize($serializedCommandData, $commandClass);
}
