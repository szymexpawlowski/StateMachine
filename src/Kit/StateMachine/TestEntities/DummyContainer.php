<?php

namespace Kit\StateMachine\TestEntities;

use Kit\StateMachine\Factory\ContainerInterface;

class DummyContainer implements ContainerInterface
{
    public function get($serviceId, $params = null)
    {
        return new $serviceId();
    }
} 