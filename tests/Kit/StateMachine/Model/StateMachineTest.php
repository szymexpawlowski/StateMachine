<?php

namespace Kit\StateMachine\Model;

/**
 * Class StateMachineTest
 *
 * @package Kit\StateMachine\Model
 */
class StateMachineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $stateMachineName = 'Kit\StateMachine\Model\StatefulInterface';

    /**
     * @var StateMachine
     */
    private $stateMachine;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->stateMachine = new StateMachine($this->stateMachineName, []);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\StateMachine::getName
     */
    public function shouldReturnName()
    {
        $this->assertEquals($this->stateMachine->getName(), $this->stateMachineName);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\StateMachine::getStates
     */
    public function shouldReturnEmptyStatesArray()
    {
        $this->assertEquals($this->stateMachine->getStates(), []);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\StateMachine::__construct
     * @covers Kit\StateMachine\Model\StateMachine::getStates
     */
    public function shouldReturnStatesSetInConstructor()
    {
        $states = ['dummy'];
        $stateMachine = new StateMachine($this->stateMachineName, $states);

        $this->assertEquals($stateMachine->getStates(), $states);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\StateMachine::addState
     * @covers Kit\StateMachine\Model\StateMachine::getStates
     */
    public function shouldAddState()
    {
        $stateStub = $this->getMock('Kit\StateMachine\Model\State', [], [], '', false);
        $this->stateMachine->addState($stateStub);

        $this->assertEquals($this->stateMachine->getStates(), [$stateStub]);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\StateMachine::addState
     * @expectedException \RuntimeException
     */
    public function shouldThrowExceptionWhenAddingStateWithTheSameNameAgain()
    {
        $stateStub = $this->getMock('Kit\StateMachine\Model\State', [], [], '', false);
        $this->stateMachine->addState($stateStub);
        $this->stateMachine->addState($stateStub);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\StateMachine::triggerAction
     * @covers Kit\StateMachine\Model\StateMachine::isEntityRegistered
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Entity is not registered for this state machine
     */
    public function shouldThrowExceptionWhenEntityIsNotRegisteredForStateMachine()
    {
        $stateMachine = new StateMachine('dummy', []);
        $statefulEntityMock = $this->getMock('Kit\StateMachine\Model\StatefulInterface', [], [], '', false);
        $stateMachine->triggerAction($statefulEntityMock, 'action', []);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\StateMachine::triggerAction
     * @covers Kit\StateMachine\Model\StateMachine::getStateByName
     * @covers Kit\StateMachine\Model\StateMachine::isEntityRegistered
     * @expectedException \RuntimeException
     * @expectedExceptionMessage State is not registered for this state machine
     */
    public function shouldThrowExceptionWhenStateIsNotInStateMachine()
    {
        $stateStub = $this->getMock('Kit\StateMachine\Model\State', [], ['stateName'], '', true);
        $entityName = 'entityName';
        $stateMachine = new StateMachine($entityName, [$stateStub]);
        $statefulEntityStub = $this->getMock('Kit\StateMachine\Model\StatefulInterface', [], [], '', false);
        $statefulEntityStub->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($entityName));
        $statefulEntityStub->expects($this->once())
            ->method('getStateName')
            ->will($this->returnValue('dummy'));

        $stateMachine->triggerAction($statefulEntityStub, 'action', []);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\StateMachine::triggerAction
     * @covers Kit\StateMachine\Model\StateMachine::getStateByName
     * @covers Kit\StateMachine\Model\StateMachine::isEntityRegistered
     */
    public function shouldCallStateExecuteMethod()
    {
        $action = 'action';
        $constraints = [];
        $stateName = 'stateName';
        $entityName = 'entityName';
        $statefulEntityStub = $this->getMock('Kit\StateMachine\Model\StatefulInterface', [], [], '', false);
        $statefulEntityStub->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($entityName));
        $statefulEntityStub->expects($this->once())
            ->method('getStateName')
            ->will($this->returnValue($stateName));

        $stateStub = $this->getMock('Kit\StateMachine\Model\State', ['triggerAction'], [$stateName], '', true);
        $stateStub->expects($this->once())
            ->method('triggerAction')
            ->with(
                $this->equalTo($statefulEntityStub),
                $this->equalTo($action),
                $this->equalTo($constraints)
            );

        $stateMachine = new StateMachine($entityName, [$stateStub]);
        $stateMachine->triggerAction($statefulEntityStub, $action, $constraints);
    }
}
