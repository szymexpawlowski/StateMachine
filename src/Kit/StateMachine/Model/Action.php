<?php

namespace Kit\StateMachine\Model;

/**
 * Class Action
 *
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
     * @param string $name     name of the action
     * @param array  $commands array of commands
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
     * @param ExecutableInterface $command
     */
    public function addCommand(ExecutableInterface $command)
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
     * @param StatefulInterface $entity      entity to change
     * @param array             $constraints constraints
     *
     * @return bool
     */
    public function execute(StatefulInterface $entity, array $constraints)
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
