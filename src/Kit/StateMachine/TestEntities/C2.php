<?php

namespace Kit\StateMachine\TestEntities;

use Kit\StateMachine\Model\ExecutableInterface;
use Kit\StateMachine\Model\StatefulInterface;

class C2 implements ExecutableInterface
{
    public function execute(StatefulInterface $entity)
    {
        $entity->decrementCounter();

        return true;
    }
} 