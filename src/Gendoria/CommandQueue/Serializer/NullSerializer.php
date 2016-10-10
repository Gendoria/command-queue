<?php

namespace Gendoria\CommandQueue\Serializer;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\Worker\Exception\TranslateErrorException;
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
    public function unserialize(SerializedCommandData $serializedCommandData)
    {
        $commandClass = $serializedCommandData->getCommandClass();
        if (!is_object($serializedCommandData->getSerializedCommand()) || !$serializedCommandData->getSerializedCommand() instanceof $commandClass) {
            throw new TranslateErrorException($serializedCommandData, "Null serializer accepts only commands as serialized command data.");
        }
        return $serializedCommandData->getSerializedCommand();
    }

}
