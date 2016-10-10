<?php

namespace Gendoria\CommandQueue\Serializer;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\Worker\Exception\TranslateErrorException;

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
     * @param SerializedCommandData $serializedCommandData
     * @return CommandInterface
     * 
     * @throws TranslateErrorException Thrown, when unserialization is impossible due to errors.
     */
    public function unserialize(SerializedCommandData $serializedCommandData);
}
