<?php

namespace Gendoria\CommandQueue\Serializer;

use Gendoria\CommandQueue\Command\CommandInterface;
use InvalidArgumentException;

/**
 * This class creates command data with no serialization at all.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class NullSerializer implements SerializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function serialize(CommandInterface $command)
    {
        return new SerializedCommandData($command, get_class($command));
    }

    /**
     * {@inheritdoc}
     * 
     * @throws InvalidArgumentException Thrown, when "serialized" data is not an instance of correct command class.
     */
    public function unserialize($serializedCommandData, $commandClass)
    {
        if (!is_object($serializedCommandData) || !$serializedCommandData instanceof $commandClass) {
            throw new InvalidArgumentException("Null serializer accepts only commands as serialized command data.");
        }
        return $serializedCommandData;
    }

}
