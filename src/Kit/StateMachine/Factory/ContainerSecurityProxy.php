<?php

namespace Kit\StateMachine\Factory;

use Kit\StateMachine\Model\ExecutableInterface;

/**
 * Class ContainerSecurityProxy
 *
 * @package Kit\StateMachine\Factory
 */
class ContainerSecurityProxy implements ContainerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function get($serviceId, $params = null)
    {
        $service = $this->container->get($serviceId, $params);

        if (!$this->isServiceValid($service)) {
            throw new \RuntimeException('Service has to be child of Action class or implements ExecutableInterface');
        }

        return $service;
    }

    /**
     * @param mixed $service
     *
     * @return bool
     */
    private function isServiceValid($service)
    {
        return $service instanceof ExecutableInterface;
    }
}
