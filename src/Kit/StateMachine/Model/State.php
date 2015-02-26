<?php

namespace Kit\StateMachine\Model;

/**
 * Class State
 *
 * @package Kit\StateMachine\Model
 */
class State
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $actions;

    /**
     * @param string $name    name od state
     * @param array  $actions array of actions
     */
    public function __construct($name, array $actions = [])
    {
        $this->name = $name;
        $this->actions = $actions;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Action $action
     *
     * @throws \RuntimeException
     */
    public function addAction(Action $action)
    {
        if ($this->getActionByName($action->getName())) {
            throw new \RuntimeException('Action already exists in state');
        }

        $this->actions[] = $action;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param string $actionName
     *
     * @return mixed
     */
    private function getActionByName($actionName)
    {
        foreach ($this->actions as $action) {
            if ($action->getName() === $actionName) {

                return $action;
            }
        }
    }

    /**
     * @param StatefulInterface $entity      entity to change
     * @param string            $action      name of action
     * @param array             $constraints constraints
     *
     * @throws \RuntimeException
     */
    public function triggerAction(StatefulInterface $entity, $action, array $constraints)
    {
        $action = $this->getActionByName($action);

        if (!$action) {
            throw new \RuntimeException('Action is not registered for this state: ' . $this->getName());
        }

        if ($action->execute($entity, $constraints)) {
            $entity->setState($action->getSuccessState());
        } else {
            $entity->setState($action->getErrorState());
        }
    }
}
