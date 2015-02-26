<?php

namespace Kit\StateMachine\Model;

interface Executable
{
    public function execute(Stateful $entity);
}
