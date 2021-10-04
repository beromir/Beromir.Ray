<?php

namespace Beromir\Ray\FusionObjects;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

class Ray extends AbstractFusionObject
{

    /**
     * @return void
     */
    public function evaluate(): void
    {
        $debugAction = strtolower($this->fusionValue('debugAction'));
        $debugValues = $this->fusionValue('debugValues');

        switch ($debugAction) {
            case strtolower('nodeTypeName'):
                $this->getNodeData($debugValues, 'getNodeTypeName');
                break;
            case strtolower('phpInfo'):
                ray()->phpinfo();
                break;
            case strtolower('backtrace'):
                ray()->backtrace();
                break;
            case strtolower('context'):
                $this->getNodeData($debugValues, 'getContext');
                break;
            case strtolower('contextPath'):
                $this->getNodeData($debugValues, 'getContextPath');
                break;
            case strtolower('properties'):
                $this->getNodeData($debugValues, 'getProperties');
                break;
            default:
                $this->debug($debugValues);
        }
    }

    /**
     * @param mixed $values
     */
    private function debug(mixed $values): void
    {
        if (!empty($values)) {
            if (is_array($values) && (get_class($values[0]) === 'Neos\ContentRepository\Domain\Model\Node')) {
                foreach ($values as $node) {
                    try {
                        ray($node);
                    } catch (Exception $e) {
                        ray($e);
                    }
                }
            } else {
                ray($values);
            }
        }
    }

    /**
     * @param mixed $values
     */
    private function getNodeData(mixed $values, string $debugOption = 'properties'): void
    {
        if (!empty($values)) {
            if (is_array($values)) {
                foreach ($values as $value) {
                    if (get_class($value) === 'Neos\ContentRepository\Domain\Model\Node') {
                        try {
                            ray($value->$debugOption());
                        } catch (Exception $e) {
                            ray($e);
                        }
                    }
                }
            } elseif (get_class($values) === 'Neos\ContentRepository\Domain\Model\Node') {
                ray($values->$debugOption());
            }
        }
    }
}
