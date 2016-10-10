<?php

namespace Gendoria\CommandQueue\Tests\Serializer;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\Serializer\NullSerializer;
use Gendoria\CommandQueue\Serializer\SerializedCommandData;
use Gendoria\CommandQueue\Worker\Exception\TranslateErrorException;
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
        
        $command2 = $serializer->unserialize($commandData);
        $this->assertEquals($command, $command2);
    }
    
    public function testUnserializationExceptionNotAnObject()
    {
        $this->setExpectedException(TranslateErrorException::class, 'Null serializer accepts only commands as serialized command data.');
        $serializer = new NullSerializer();
        
        $serializer->unserialize(new SerializedCommandData("NotACommand", "NotAClass"));
    }
    
    public function testUnserializationExceptionIncorrectClass()
    {
        $this->setExpectedException(TranslateErrorException::class, 'Null serializer accepts only commands as serialized command data.');
        $serializer = new NullSerializer();
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        
        $serializer->unserialize(new SerializedCommandData($command, "NotAClass"));
    }    
}
