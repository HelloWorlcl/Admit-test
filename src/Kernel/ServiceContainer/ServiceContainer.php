<?php

namespace App\Kernel\ServiceContainer;

class ServiceContainer
{
    /**
     * @var array
     */
    private $singletons;

    /**
     * @var array
     */
    private $dependencies;

    public function __construct()
    {
        $this->dependencies = require_once 'dependencies.php';
    }

    public function setSingleton($key, $object): ServiceContainer
    {
        $this->singletons[$key] = $object;

        return $this;
    }

    public function getObjectWithDependencies(string $objectPath): object
    {
        if (array_key_exists($objectPath, $this->dependencies)) {
            return $this->resolveDependencies($objectPath);
        }

        return new $objectPath;
    }

    private function resolveDependencies(string $objectPath): object
    {
        $dependenciesObjects = [];
        foreach ($this->dependencies[$objectPath] as $dependency) {
            if (!array_key_exists($dependency, $this->singletons)) {
                $dependenciesObjects[] = $this->createDependency($dependency);
            } else {
                $dependenciesObjects[] = $this->singletons[$dependency];
            }
        }

        return new $objectPath(...$dependenciesObjects);
    }

    private function createDependency(string $dependencyPath): object
    {
        if (array_key_exists($dependencyPath, $this->dependencies)) {
            return $this->resolveDependencies($dependencyPath);
        } else {
            return new $dependencyPath();
        }
    }
}
