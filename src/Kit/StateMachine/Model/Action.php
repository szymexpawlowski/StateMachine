<?php

namespace Kit\StateMachine\Model;

/**
 * Class Action
 * @package Kit\StateMachine\Entity
 */
abstract class Action
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var State
     */
    private $successState;

    /**
     * @var State
     */
    private $errorState;

    /**
     * @var array
     */
    private $commands;

    /**
     * @param string $name
     * @param array  $commands
     *
     * @codeCoverageIgnore
     * abstract class and no concrete classes will inherit from it in this lib so
     */
    public function __construct($name, array $commands = [])
    {
        $this->name = $name;
        $this->commands = $commands;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param State $errorState
     */
    public function setErrorState(State $errorState = null)
    {
        $this->errorState = $errorState;
    }

    /**
     * @return State
     */
    public function getErrorState()
    {
        return $this->errorState;
    }

    /**
     * @param State $successState
     */
    public function setSuccessState(State $successState = null)
    {
        $this->successState = $successState;
    }

    /**
     * @return State
     */
    public function getSuccessState()
    {
        return $this->successState;
    }

    /**
     * @param Executable $command
     */
    public function addCommand(Executable $command)
    {
        $this->commands[] = $command;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param Stateful $entity
     * @param array    $constraints
     *
     * @return bool
     */
    public function execute(Stateful $entity, array $constraints)
    {
        if (!$this->checkConstraints($constraints)) {
            return false;
        }

        foreach ($this->commands as $command) {
            if (!$command->execute($entity)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $constraints
     *
     * @return bool
     */
    abstract protected function checkConstraints(array $constraints);
}
