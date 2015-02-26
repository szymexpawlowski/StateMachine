<?php

namespace Kit\StateMachine\Model;

/**
 * Class State
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
     * @param string $name
     * @param array  $actions
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
     */
    public function addAction(Action $action)
    {
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
     * @param Stateful $entity
     * @param string   $action
     * @param array    $constraints
     *
     * @throws \RuntimeException
     */
    public function triggerAction(Stateful $entity, $action, array $constraints)
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