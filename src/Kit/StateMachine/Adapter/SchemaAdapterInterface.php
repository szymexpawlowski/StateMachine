<?php

namespace Kit\StateMachine\Adapter;

/**
 * Interface SchemaAdapterInterface
 *
 * @package Kit\StateMachine\Adapter
 */
interface SchemaAdapterInterface
{
    /**
     * @return string
     */
    public function getStateMachineName();

    /**
     * @return array
     */
    public function getStateNames();

    /**
     * @param string $stateName
     *
     * @return array
     */
    public function getActionNamesForState($stateName);

    /**
     * @param string $stateName  state name
     * @param string $actionName action name
     *
     * @return array
     */
    public function getActionDefinition($stateName, $actionName);
}
