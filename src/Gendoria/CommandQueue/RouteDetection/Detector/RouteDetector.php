<?php

namespace Gendoria\CommandQueue\RouteDetection\Detector;

use Gendoria\CommandQueue\RouteDetection\Detection\ClassDetection;
use Gendoria\CommandQueue\RouteDetection\Detection\DefaultDetection;
use Gendoria\CommandQueue\RouteDetection\Detection\DetectionInterface;
use InvalidArgumentException;
use ReflectionClass;

/**
 * Detector class used for match class expressions with arbitrary routes.
 *
 * @author Tomasz Struczyński <tomasz.struczynski@isobar.com>
 */
class RouteDetector
{
    /**
     * Simple routes in a form of [Class] => [PoolName].
     *
     * @var array
     */
    private $simpleRoutes = array();

    /**
     * Expression routes in a form of [ClassExpression] => [PoolName].
     *
     * @var array
     */
    private $regexpRoutes = array();

    /**
     * Default route.
     *
     * @var string
     */
    private $defaultRoute = '';

    /**
     * Class constructor.
     */
    public function __construct($defaultRoute = '')
    {
        $this->setDefault($defaultRoute);
    }

    /**
     * Add new route.
     *
     * @param string $expression Either simple expression, or RegExp describing route.
     * @param string $route
     *
     * @return bool True, if route has been set, false otherwise.
     */
    public function addRoute($expression, $route)
    {
        //Detect command expression
        if (strpos($expression, '*') !== false) {
            $expression = '|'.str_replace(array('*', '\\'), array('.*', '\\\\'), $expression).'|i';
            if (array_key_exists($expression, $this->regexpRoutes) && $this->regexpRoutes[$expression] == $route) {
                return false;
            }
            $this->regexpRoutes[$expression] = (string) $route;
        } else {
            if (array_key_exists($expression, $this->simpleRoutes) && $this->simpleRoutes[$expression] == $route) {
                return false;
            }
            $this->simpleRoutes[$expression] = (string) $route;
        }

        return true;
    }

    /**
     * Set default route.
     *
     * @param string $route
     */
    public function setDefault($route)
    {
        $this->defaultRoute = (string) $route;
    }

    /**
     * Get default route.
     *
     * @return string
     */
    protected function getDefault()
    {
        return $this->defaultRoute;
    }

    /**
     * Detect correct route for given class.
     *
     * @param string $className
     *
     * @return DetectionInterface
     *
     * @throws InvalidArgumentException Thrown, if argument is not a class name.
     */
    public function detect($className)
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentexception('Given name is not a class');
        }

        return $this->doDetect($className)->getPoolName();
    }

    /**
     * Detect pool.
     *
     * @param string $className
     * @param boolean $performInterfaceDetection
     *
     * @return DetectionInterface
     */
    private function doDetect($className, $performInterfaceDetection = true)
    {
        $detection = $this->doDetectByRoutes($className);
        if ($detection) {
            return $detection;
        }
        //Nothing is found so far. We will check all of the class interfaces and base classes.
        //First - check base classes up to the 'root'
        $parentClass = get_parent_class($className);
        if ($parentClass) {
            $parentDetection = $this->doDetect($parentClass, false);
            if ($parentDetection instanceof ClassDetection) {
                return $parentDetection;
            }
        }
        //Check the class interfaces
        if ($performInterfaceDetection) {
            $definition = $this->doDetectByInterfaces($className);
            if ($definition) {
                return $definition;
            }
        }

        return new DefaultDetection($this->defaultRoute);
    }

    /**
     * Perform detection based on class name and routes registered for this class.
     *
     * @param string $className
     *
     * @return ClassDetection|null
     */
    private function doDetectByRoutes($className)
    {
        //If we have entry for a command class, we should always return it, as it is most specific.
        if (!empty($this->simpleRoutes[$className])) {
            return new ClassDetection($this->simpleRoutes[$className]);
        }
        //Now, we should check, if we have 'regexp' entry for class
        foreach ($this->regexpRoutes as $regexpRoute => $poolName) {
            if (preg_match($regexpRoute, $className)) {
                return new ClassDetection($poolName);
            }
        }
    }

    /**
     * Perform detection based on class interfaces.
     *
     * @param string $className
     *
     * @return DetectionInterface|null
     */
    private function doDetectByInterfaces($className)
    {
        $interfaces = $this->getOrderedInterfaces($className);
        foreach ($interfaces as $interface) {
            $candidate = $this->doDetectByRoutes($interface);
            if ($candidate) {
                return $candidate;
            }
        }

        return;
    }

    /**
     * Get a list of class interfaces ordered by plase of interface declaration.
     *
     * The list is ordered by place of interface declaration. Interfaces declared on most child class are first,
     * while those in base class(es) - last.
     *
     * @param string $className
     *
     * @return array
     */
    private function getOrderedInterfaces($className)
    {
        $interfacesArr = array();
        $classParents = class_parents($className);
        $reflection = new ReflectionClass($className);
        $interfacesArr[] = $reflection->getInterfaceNames();
        if (empty($interfacesArr[0])) {
            return array();
        }
        foreach ($classParents as $parentClass) {
            $reflection = new ReflectionClass($parentClass);
            array_unshift($interfacesArr, $reflection->getInterfaceNames());
            if (empty($interfacesArr[0])) {
                break;
            }
        }
        $interfaces = array();
        foreach ($interfacesArr as $classInterfaces) {
            foreach ($classInterfaces as $interface) {
                if (array_search($interface, $interfaces) === false) {
                    array_unshift($interfaces, $interface);
                }
            }
        }

        return $interfaces;
    }
}
