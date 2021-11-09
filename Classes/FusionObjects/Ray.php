<?php

namespace Beromir\Ray\FusionObjects;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Fusion\FusionObjects\AbstractArrayFusionObject;

class Ray extends AbstractArrayFusionObject
{
    /**
     * If you iterate over "properties" these in here should usually be ignored.
     * For example additional properties in "Case" that are not "Matchers".
     *
     * @var array
     */
    protected $ignoreProperties = ['__meta', 'debugAction', 'value'];

    protected $allowedDebugActions = ['nodetypename', 'context', 'contextpath', 'properties'];

    /**
     * @return void
     */
    public function evaluate(): void
    {
        $debugAction = strtolower($this->fusionValue('debugAction'));
        $debugValue = $this->fusionValue('value');

        if (is_string($debugAction) && in_array($debugAction, $this->allowedDebugActions)) {
            $this->debugNodes($debugValue, $debugAction);
        } elseif (is_string($debugAction) && !empty($debugAction)) {
            ray()->once('The debug action is not known.');
        } else {
            $this->debug($debugValue);
        }
    }

    /**
     * @param $debugValue
     *
     * @return void
     */
    private function debug($debugValue): void
    {
        ray(function () use ($debugValue) {

            $debug = ray($debugValue);

            foreach (array_keys($this->properties) as $key) {
                if (in_array($key, $this->ignoreProperties)) {
                    continue;
                }

                $value = $this->fusionValue($key);

                if (empty($value)) {
                    $debug = $debug->$key();
                } else {
                    $debug = $debug->$key($value);
                }
            }
            return $debug;
        });
    }

    /**
     * @param $debugValue
     * @param string $debugAction
     *
     */
    private function getNodeData($debugValue, string $debugAction)
    {
        switch ($debugAction) {
            case strtolower('nodeTypeName'):
                try {
                    return $debugValue->getNodeTypeName();
                } catch (Exception $e) {
                    ray()->exception($e);
                    return null;
                }
            case strtolower('context'):
                return $debugValue->getContext();
            case strtolower('contextPath'):
                return $debugValue->getContextPath();
            case strtolower('properties'):
                try {
                    return $debugValue->getProperties();
                } catch (Exception $e) {
                    ray()->exception($e);
                    return null;
                }
            default:
                return null;
        }
    }

    /**
     * @param $debugValues
     * @param string $debugAction
     *
     * @return void
     */
    private function debugNodes($debugValues, string $debugAction): void
    {
        if (is_array($debugValues)) {
            $debugArray = [];

            foreach ($debugValues as $debugValue) {
                if (is_object($debugValue) && (get_class($debugValue) === 'Neos\ContentRepository\Domain\Model\Node')) {
                    array_push($debugArray, $this->getNodeData($debugValue, $debugAction));
                }
            }

            if (!empty($debugArray)) $this->debug($debugArray);
        } elseif (is_object($debugValues) && (get_class($debugValues) === 'Neos\ContentRepository\Domain\Model\Node')) {
            $this->debug($this->getNodeData($debugValues, $debugAction));
        }
    }
}
