<?php

namespace Kit\StateMachine\Model;

interface Stateful
{
    public function getName();
    public function getState();
    public function getStateName();
    public function setState($state);
}
