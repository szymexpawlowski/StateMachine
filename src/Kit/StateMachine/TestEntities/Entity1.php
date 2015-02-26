<?php

namespace Kit\StateMachine\TestEntities;

use Kit\StateMachine\Model\State;
use Kit\StateMachine\Model\StatefulInterface;

class Entity1 implements StatefulInterface
{
    private $state;

    private $counter = 0;

    public function incrementCounter()
    {
        $this->counter++;
    }

    public function decrementCounter()
    {
        $this->counter--;
    }

    public function getName()
    {
        return 'Entity1';
    }

    public function getState()
    {
        return $this->state;
    }

    public function getStateName()
    {
        return $this->state->getName();
    }

    public function setState(State $state)
    {
        $this->state = $state;
    }
} 