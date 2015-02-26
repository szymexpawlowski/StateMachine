<?php

namespace Kit\StateMachine\TestEntities;

use Kit\StateMachine\Model\Action;
use Kit\StateMachine\Model\StatefulInterface;

class A1 extends Action
{
    function checkConstraints(array $constraints)
    {
        return true;
    }
} 