<?php

namespace Gendoria\CommandQueue\ProcessorFactory;

use Exception;

/**
 * Thrown, when processor for given command class is not found.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class ProcessorNotFoundException extends Exception
{
}
