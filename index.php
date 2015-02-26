<?php

require_once 'vendor/autoload.php';

$yamlAdapter = new \Kit\StateMachine\Adapter\YamlAdapter(new Symfony\Component\Yaml\Parser(), file_get_contents('test.yml'));
$yamlAdapter->getStateMachineName();
$csp = new \Kit\StateMachine\Factory\ContainerSecurityProxy(new \Kit\StateMachine\TestEntities\DummyContainer());
$smf = new \Kit\StateMachine\Factory\StateMachineFactory(new \Kit\StateMachine\Factory\ActionFactory(), $csp);
$stateMachine = $smf->get($yamlAdapter);
$state1 = new \Kit\StateMachine\Model\State('state1Name');
$e1 = new Kit\StateMachine\TestEntities\Entity1();
$e1->setState($state1);
$stateMachine->triggerAction($e1, 'action1Name', []);