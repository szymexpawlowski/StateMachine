<?php

namespace Kit\StateMachine\Model;

/**
 * Interface StatefulInterface
 *
 * @package Kit\StateMachine\Model
 */
interface StatefulInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return State
     */
    public function getState();

    /**
     * @return string
     */
    public function getStateName();

    /**
     * @param State $state
     */
    public function setState(State $state);
}
