<?php

namespace Kit\StateMachine\TestEntities;

use Kit\StateMachine\Model\State;
use Kit\StateMachine\Model\StatefulInterface;

class Entity2 implements StatefulInterface
{
    private $state;

    public function getState()
    {
        return $this->state;
    }

    public function setState(State $state)
    {
        $this->state = $state;
    }

    public function getStateName()
    {
        return $this->state->getName();
    }

    public function getName()
    {
        return 'Entity2';
    }
} 