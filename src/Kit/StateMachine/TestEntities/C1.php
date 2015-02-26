<?php

namespace Kit\StateMachine\TestEntities;

use Kit\StateMachine\Model\ExecutableInterface;
use Kit\StateMachine\Model\StatefulInterface;

class C1 implements ExecutableInterface
{
    public function execute(StatefulInterface $entity)
    {
        $entity->incrementCounter();
        $entity->incrementCounter();
        $entity->incrementCounter();

        return true;
    }
} 