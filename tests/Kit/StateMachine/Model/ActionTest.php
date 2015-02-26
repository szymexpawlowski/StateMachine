<?php

namespace Kit\StateMachine\Model;

/**
 * Class ActionTest
 *
 * @package Kit\StateMachine\Model
 */
class ActionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $actionName = 'action1';

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $actionStub;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->actionStub = $this->getMockForAbstractClass('Kit\StateMachine\Model\Action', [$this->actionName, []]);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\Action::getName
     */
    public function shouldReturnName()
    {
        $this->assertEquals($this->actionStub->getName(), $this->actionName);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\Action::getCommands
     */
    public function shouldReturnEmptyCommandsArray()
    {
        $this->assertEquals($this->actionStub->getCommands(), []);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\Action::getCommands
     */
    public function shouldReturnCommandsSetInConstructor()
    {
        $commands = ['dummy'];
        $actionStub = $this->getMockForAbstractClass('Kit\StateMachine\Model\Action', [$this->actionName, $commands]);

        $this->assertEquals($actionStub->getCommands(), $commands);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\Action::addCommand
     * @covers Kit\StateMachine\Model\Action::getCommands
     */
    public function shouldAddCommand()
    {
        $command = $this->getMock('Kit\StateMachine\Model\ExecutableInterface');
        $this->actionStub->addCommand($command);

        $this->assertEquals($this->actionStub->getCommands(), [$command]);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\Action::getSuccessState
     * @covers Kit\StateMachine\Model\Action::setSuccessState
     */
    public function shouldGetAndSetSuccessState()
    {
        $state = $this->getMock('Kit\StateMachine\Model\State', [], [], '', false);
        $this->actionStub->setSuccessState($state);

        $this->assertEquals($this->actionStub->getSuccessState(), $state);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\Action::getErrorState
     * @covers Kit\StateMachine\Model\Action::setErrorState
     */
    public function shouldGetAndSetErrorState()
    {
        $state = $this->getMock('Kit\StateMachine\Model\State', [], [], '', false);
        $this->actionStub->setErrorState($state);

        $this->assertEquals($this->actionStub->getErrorState(), $state);
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\Action::execute
     */
    public function shouldReturnFalseWhenConstraintsFail()
    {
        $commands = ['dummy'];
        $actionStub = $this->getMockForAbstractClass('Kit\StateMachine\Model\Action', [$this->actionName, $commands]);
        $this->setCheckConstraints($actionStub, false);
        $statefulEntity = $this->getMock('Kit\StateMachine\Model\StatefulInterface');

        $this->assertFalse($actionStub->execute($statefulEntity, []));
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\Action::execute
     */
    public function shouldReturnFalseWhenAtLeastOneOfCommandsFail()
    {
        $command1 = $this->createCommand(true);
        $command2 = $this->createCommand(false);
        $actionStub = $this->getMockForAbstractClass(
            'Kit\StateMachine\Model\Action',
            [$this->actionName, [$command1, $command2]]
        );
        $this->setCheckConstraints($actionStub, true);
        $statefulEntity = $this->getMock('Kit\StateMachine\Model\StatefulInterface');

        $this->assertFalse($actionStub->execute($statefulEntity, []));
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\Action::execute
     */
    public function shouldReturnTrueWhenThereIsNoCommands()
    {
        $actionStub = $this->getMockForAbstractClass(
            'Kit\StateMachine\Model\Action',
            [$this->actionName, []]
        );
        $this->setCheckConstraints($actionStub, true);
        $statefulEntity = $this->getMock('Kit\StateMachine\Model\StatefulInterface');

        $this->assertTrue($actionStub->execute($statefulEntity, []));
    }

    /**
     * @test
     * @covers Kit\StateMachine\Model\Action::execute
     */
    public function shouldReturnTrueWhenAllCommandsPass()
    {
        $command1 = $this->createCommand(true);
        $command2 = $this->createCommand(true);
        $actionStub = $this->getMockForAbstractClass(
            'Kit\StateMachine\Model\Action',
            [$this->actionName, [$command1, $command2]]
        );
        $this->setCheckConstraints($actionStub, true);
        $statefulEntity = $this->getMock('Kit\StateMachine\Model\StatefulInterface');

        $this->assertTrue($actionStub->execute($statefulEntity, []));
    }

    /**
     * @param bool $executeReturnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createCommand($executeReturnValue)
    {
        $command = $this->getMock('Kit\StateMachine\Model\ExecutableInterface');
        $command->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($executeReturnValue));

        return $command;
    }

    /**
     * @param mixed $action                      action
     * @param bool  $checkConstraintsReturnValue constraints return value
     */
    private function setCheckConstraints($action, $checkConstraintsReturnValue)
    {
        $action->expects($this->any())
            ->method('checkConstraints')
            ->will($this->returnValue($checkConstraintsReturnValue));
    }
}
