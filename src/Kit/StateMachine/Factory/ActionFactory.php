<?php

namespace Kit\StateMachine\Factory;

use Kit\StateMachine\Model\Action;
use Kit\StateMachine\Model\State;

/**
 * Class ActionFactory
 *
 * @package Kit\StateMachine\Factory
 */
class ActionFactory
{
    /**
     * @param string $actionClass  action class
     * @param string $actionName   action name
     * @param State  $successState success state
     * @param State  $errorState   error state
     *
     * @return Action
     * @throws \RuntimeException
     */
    public function get($actionClass, $actionName, State $successState, State $errorState)
    {
        if (!class_exists($actionClass)) {
            throw new \RuntimeException($actionClass . ' doesn\'t exist');
        }

        $action = new $actionClass($actionName);
        $action->setSuccessState($successState);
        $action->setErrorState($errorState);

        return $action;
    }
}
