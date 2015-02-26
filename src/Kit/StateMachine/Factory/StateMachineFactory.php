<?php

namespace Kit\StateMachine\Factory;

use Kit\StateMachine\Adapter\SchemaAdapterInterface;
use Kit\StateMachine\Model\Action;
use Kit\StateMachine\Model\State;
use Kit\StateMachine\Model\StateMachine;

/**
 * Class StateMachineFactory
 *
 * @package Kit\StateMachine\Factory
 */
class StateMachineFactory
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var ContainerSecurityProxy
     */
    private $containerSecurityProxy;

    /**
     * @var array
     */
    private $stateMap = [];

    /**
     * @param ActionFactory          $actionFactory          action factory
     * @param ContainerSecurityProxy $containerSecurityProxy container security proxy
     */
    public function __construct(ActionFactory $actionFactory, ContainerSecurityProxy $containerSecurityProxy)
    {
        $this->actionFactory = $actionFactory;
        $this->containerSecurityProxy = $containerSecurityProxy;
    }

    /**
     * @param SchemaAdapterInterface $schemaAdapter
     *
     * @return StateMachine
     */
    public function get(SchemaAdapterInterface $schemaAdapter)
    {
        $stateMachine = new StateMachine($schemaAdapter->getStateMachineName());
        $stateNames = $schemaAdapter->getStateNames();
        $this->createStateMap($stateNames);

        foreach ($this->stateMap as $state) {

            $this->addActionToState($schemaAdapter, $state);
            $stateMachine->addState($state);
        }

        return $stateMachine;
    }

    /**
     * @param array $stateNames
     */
    private function createStateMap(array $stateNames)
    {
        foreach ($stateNames as $stateName) {
            $state = new State($stateName);
            $this->stateMap[$stateName] = $state;
        }
    }

    /**
     * @param string $stateName
     *
     * @return State
     */
    private function getStateFromStateMap($stateName)
    {
        return $this->stateMap[$stateName];
    }

    /**
     * @param string $actionName       action name
     * @param array  $actionDefinition array with action definition
     *
     * @return Action
     */
    private function createAction($actionName, array $actionDefinition)
    {
        $action = $this->actionFactory->get(
            $actionDefinition['actionClass'],
            $actionName,
            $this->getStateFromStateMap($actionDefinition['successState']),
            $this->getStateFromStateMap($actionDefinition['errorState'])
        );

        $this->addCommands($action, $actionDefinition['commands']);

        return $action;
    }

    /**
     * @param SchemaAdapterInterface $schemaAdapter schema adapter
     * @param State                  $state         state
     */
    private function addActionToState(SchemaAdapterInterface $schemaAdapter, State $state)
    {
        $actionNames = $schemaAdapter->getActionNamesForState($state->getName());
        foreach ($actionNames as $actionName) {

            $actionDefinition = $schemaAdapter->getActionDefinition($state->getName(), $actionName);
            $action = $this->createAction($actionName, $actionDefinition);
            $state->addAction($action);
        }
    }

    /**
     * @param Action $action   action
     * @param array  $commands array of commands
     */
    private function addCommands(Action $action, array $commands)
    {
        foreach ($commands as $command) {
            $action->addCommand($this->containerSecurityProxy->get($command));
        }
    }
}
