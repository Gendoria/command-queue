<?php

namespace Gendoria\CommandQueue\Serializer;

use Exception;
use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\Serializer\Exception\UnserializeErrorException;

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
     * @throws Exception Thrown, when serialization process failed.
     */
    public function serialize(CommandInterface $command);
    
    /**
     * Unserialize command.
     * 
     * @param SerializedCommandData $serializedCommandData
     * @return CommandInterface
     * 
     * @throws UnserializeErrorException Thrown, when unserialization is impossible due to errors.
     */
    public function unserialize(SerializedCommandData $serializedCommandData);
}
