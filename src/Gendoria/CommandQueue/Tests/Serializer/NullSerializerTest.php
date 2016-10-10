<?php

namespace Gendoria\CommandQueue\Tests\Serializer;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\Serializer\NullSerializer;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * Description of NullSerializerTest
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class NullSerializerTest extends PHPUnit_Framework_TestCase
{
    public function testCorrect()
    {
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $serializer = new NullSerializer();
        $commandData = $serializer->serialize($command);
        $this->assertEquals(get_class($command), $commandData->getCommandClass());
        $this->assertEquals($command, $commandData->getSerializedCommand());
        
        $command2 = $serializer->unserialize($commandData->getSerializedCommand(), $commandData->getCommandClass());
        $this->assertEquals($command, $command2);
    }
    
    public function testUnserializationExceptionNotAnObject()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Null serializer accepts only commands as serialized command data.');
        $serializer = new NullSerializer();
        
        $serializer->unserialize("NotACommand", "NotAClass");
    }
    
    public function testUnserializationExceptionIncorrectClass()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Null serializer accepts only commands as serialized command data.');
        $serializer = new NullSerializer();
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        
        $serializer->unserialize($command, "NotAClass");
    }    
}
