<?php

namespace Kit\StateMachine\Factory;

/**
 * Interface ContainerInterface
 *
 * @package Kit\StateMachine\Factory
 */
interface ContainerInterface
{
    /**
     * @param string     $serviceId service id
     * @param mixed|null $params    parameter bag
     *
     * @return mixed
     */
    public function get($serviceId, $params = null);
}
