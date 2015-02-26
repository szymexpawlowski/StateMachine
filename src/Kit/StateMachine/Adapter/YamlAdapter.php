<?php

namespace Kit\StateMachine\Adapter;

use Symfony\Component\Yaml\Parser;

/**
 * Class YamlAdapter
 *
 * @package Kit\StateMachine\Adapter
 */
class YamlAdapter implements SchemaAdapterInterface
{
    /**
     * @var \Symfony\Component\Yaml\Parser
     */
    private $parsedData;

    /**
     * @var string
     */
    private $cachedStateMachineName;

    /**
     * @param Parser $yamlParser yaml parser
     * @param string $filePath   file path
     */
    public function __construct(Parser $yamlParser, $filePath)
    {
        $this->parsedData = $yamlParser->parse($filePath);
    }

    /**
     * {@inheritDoc}
     */
    public function getStateMachineName()
    {
        if (!isset($this->cachedStateMachineName)) {
            reset($this->parsedData);
            $this->cachedStateMachineName = key($this->parsedData);
        }

        return $this->cachedStateMachineName;
    }

    /**
     * {@inheritDoc}
     */
    public function getStateNames()
    {
        return array_keys($this->parsedData[$this->getStateMachineName()]);
    }

    /**
     * {@inheritDoc}
     */
    public function getActionNamesForState($stateName)
    {
        return array_keys($this->parsedData[$this->getStateMachineName()][$stateName]);
    }

    /**
     * {@inheritDoc}
     */
    public function getActionDefinition($stateName, $actionName)
    {
        return $this->parsedData[$this->getStateMachineName()][$stateName][$actionName];
    }
}
