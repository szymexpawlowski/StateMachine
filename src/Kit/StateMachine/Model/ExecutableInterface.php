<?php

namespace Kit\StateMachine\Model;

/**
 * Interface ExecutableInterface
 *
 * @package Kit\StateMachine\Model
 */
interface ExecutableInterface
{
    /**
     * @param StatefulInterface $entity
     *
     * @return mixed
     */
    public function execute(StatefulInterface $entity);
}
