<?php

namespace Kit\StateMachine\Model;

class StateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $stateName = 'state';

    /**
     * @var State
     */
    private $state;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->state = new State($this->stateName, []);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\State::getName
     */
    public function shouldReturnName()
    {
        $this->assertEquals($this->state->getName(), $this->stateName);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\State::getActions
     */
    public function shouldReturnEmptyActionsArray()
    {
        $this->assertEquals($this->state->getActions(), []);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\State::__construct
     * @covers Kit\StateMachine\Model\State::getActions
     */
    public function shouldReturnActionsSetInConstructor()
    {
        $actions = ['dummy'];
        $state = new State($this->stateName, $actions);

        $this->assertEquals($state->getActions(), $actions);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\State::addAction
     * @covers Kit\StateMachine\Model\State::getActions
     */
    public function shouldAddAction()
    {
        $actionStub = $this->getMockForAbstractClass('Kit\StateMachine\Model\Action', ['dummy', []]);
        $this->state->addAction($actionStub);

        $this->assertEquals($this->state->getActions(), [$actionStub]);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\State::triggerAction
     * @covers Kit\StateMachine\Model\State::getActionByName
     *
     * @expectedException \RuntimeException
     */
    public function shouldThrowExceptionWhenNoActionFound()
    {
        $statefulEntity = $this->getMock('Kit\StateMachine\Model\Stateful');
        $this->state->triggerAction($statefulEntity, 'action', []);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\State::triggerAction
     * @covers Kit\StateMachine\Model\State::getActionByName
     */
    public function shouldSetStateToSuccessStateWhenActionExecuteReturnsTrue()
    {
        $actionName = 'action';
        $successfulState = $this->getMock('Kit\StateMachine\Model\State', [], [], '', false);
        $actionStub = $this->getActionStub($actionName, ['getName' => $actionName, 'execute' => true, 'getSuccessState' => $successfulState]);
        $statefulEntityMock = $this->getStatefulEntityMock($successfulState);
        $this->state->addAction($actionStub);
        $this->state->triggerAction($statefulEntityMock, $actionName, []);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\State::triggerAction
     * @covers Kit\StateMachine\Model\State::getActionByName
     */
    public function shouldSetStateToErrorStateWhenActionExecuteReturnsFalse()
    {
        $actionName = 'action';
        $errorState = $this->getMock('Kit\StateMachine\Model\State', [], [], '', false);
        $actionStub = $this->getActionStub($actionName, ['getName' => $actionName, 'execute' => false, 'getErrorState' => $errorState]);
        $statefulEntityMock = $this->getStatefulEntityMock($errorState);

        $this->state->addAction($actionStub);
        $this->state->triggerAction($statefulEntityMock, $actionName, []);
    }

    /**
     * @param $state
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getStatefulEntityMock($state)
    {
        $statefulEntityMock = $this->getMock('Kit\StateMachine\Model\Stateful');
        $statefulEntityMock->expects($this->once())
            ->method('setState')
            ->with($this->equalTo($state));

        return $statefulEntityMock;
    }

    /**
     * @param string $actionName
     * @param array  $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getActionStub($actionName, array $methods)
    {
        $actionStub = $this->getMockForAbstractClass('Kit\StateMachine\Model\Action', [$actionName, []], '', false, false, false, array_keys($methods));

        foreach ($methods as $methodName => $returnValue) {
            $actionStub->expects($this->any())
                ->method($methodName)
                ->will($this->returnValue($returnValue));
        }

        return $actionStub;
    }
}
 