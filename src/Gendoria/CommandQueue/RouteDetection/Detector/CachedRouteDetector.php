<?php

namespace Gendoria\CommandQueue\RouteDetection\Detector;

use Gendoria\CommandQueue\RouteDetection\Detection\DetectionInterface;

/**
 * Cached route detector caches detection result to speed up further searches for given class.
 *
 * @author Tomasz Struczyński <t.struczynski@gmail.com>
 */
class CachedRouteDetector extends RouteDetector
{
    /**
     * Array of cached routes.
     *
     * @var DetectionInterface[]
     */
    private $cachedRoutes = array();

    /**
     * {@inheritdoc}
     * @return DetectionInterface
     */
    public function detect($className)
    {
        if (!empty($this->cachedRoutes[$className])) {
            return $this->cachedRoutes[$className];
        }
        $this->cachedRoutes[$className] = parent::detect($className);

        return $this->cachedRoutes[$className];
    }

    /**
     * {@inheritdoc}
     */
    public function addRoute($expression, $poolName)
    {
        $return = parent::addRoute($expression, $poolName);
        if ($return) {
            $this->clearCache();
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefault($route)
    {
        if ($this->getDefault() == $route) {
            return;
        }
        parent::setDefault($route);
        $this->clearCache();
    }

    /**
     * Clear route cache.
     */
    private function clearCache()
    {
        $this->cachedRoutes = array();
    }
}
