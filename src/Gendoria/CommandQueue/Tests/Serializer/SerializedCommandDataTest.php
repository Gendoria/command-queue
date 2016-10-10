<?php

namespace Gendoria\CommandQueue\Tests\Serializer;

use Gendoria\CommandQueue\Command\CommandInterface;
use Gendoria\CommandQueue\Serializer\SerializedCommandData;
use PHPUnit_Framework_TestCase;

/**
 * Description of SerializedCommandDataTest
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class SerializedCommandDataTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $commandData1 = new SerializedCommandData("test", "test");
        $this->assertEquals("test", $commandData1->getSerializedCommand());
        $this->assertEquals("test", $commandData1->getCommandClass());
        
        $command = $this->getMockBuilder(CommandInterface::class)->getMock();
        $commandData2 = new SerializedCommandData($command, get_class($command));        
        $this->assertEquals($command, $commandData2->getSerializedCommand());
        $this->assertEquals(get_class($command), $commandData2->getCommandClass());
    }
}
