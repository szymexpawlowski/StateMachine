<?php

namespace Kit\StateMachine\Model;

/**
 * Class StateMachine
 * @package Kit\StateMachine\Entity
 */
class StateMachine
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $states;

    /**
     * @param string $name
     * @param array  $states
     */
    public function __construct($name, array $states = [])
    {
        $this->name = $name;
        $this->states = $states;
    }

    /**
     * @param Stateful $entity
     * @param          $action
     * @param array    $constraints
     *
     * @throws \RuntimeException
     */
    public function triggerAction(Stateful $entity, $action, array $constraints)
    {
        if (!$this->isEntityRegistered($entity)) {
            throw new \RuntimeException('Entity is not registered for this state machine');
        }

        $state = $this->getStateByName($entity->getStateName());
        if (!$state) {
            throw new \RuntimeException('State is not registered for this state machine');
        }

        $state->triggerAction($entity, $action, $constraints);
    }

    /**
     * @param Stateful $entity
     *
     * @return bool
     */
    private function isEntityRegistered(Stateful $entity)
    {
        return $this->getName() === $entity->getName();
    }

    /**
     * @param $stateName
     *
     * @return mixed
     */
    private function getStateByName($stateName)
    {
        foreach ($this->states as $state) {
            if ($state->getName() === $stateName) {

                return $state;
            }
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param State $state
     */
    public function addState(State $state)
    {
        $this->states[] = $state;
    }

    /**
     * @return array
     */
    public function getStates()
    {
        return $this->states;
    }
}
