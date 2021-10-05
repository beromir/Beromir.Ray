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

    /**
     * @return void
     */
    public function evaluate(): void
    {
        $debugAction = strtolower($this->fusionValue('debugAction'));
        $debugValue = $this->fusionValue('value');

        if (!empty($this->fusionValue('once'))) {
            $this->debug(null, $debugAction);
        } else {
            if (!empty($debugValue)) {
                if (is_array($debugValue) && is_object($debugValue[0]) && (get_class($debugValue[0]) === 'Neos\ContentRepository\Domain\Model\Node')) {
                    foreach ($debugValue as $node) {
                        try {
                            $this->debug($node, $debugAction);
                        } catch (Exception $e) {
                            ray()->exception($e);
                        }
                    }
                } else {
                    $this->debug($debugValue, $debugAction);
                }
            }
        }
    }

    /**
     * @param mixed $debugValue
     * @param string $debugAction
     *
     * @return void
     */
    private function debug(mixed $debugValue = null, string $debugAction = ''): void
    {
        ray(function () use ($debugValue, $debugAction) {

            $debug = ray();

            if (!empty($debugValue) && !empty($debugAction)) {
                if (is_object($debugValue) && (get_class($debugValue) === 'Neos\ContentRepository\Domain\Model\Node')) {
                    $debug = ray($this->getNodeData($debugValue, $debugAction));
                }
            } elseif (!empty($debugValue)) {
                $debug = ray($debugValue);
            }

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
     * @param object $debugValue
     * @param string $debugAction
     *
     * @return mixed
     */
    private function getNodeData(object $debugValue, string $debugAction): mixed
    {
        if (get_class($debugValue) !== 'Neos\ContentRepository\Domain\Model\Node') {
            return null;
        }

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
}
