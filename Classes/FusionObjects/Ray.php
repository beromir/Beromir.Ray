<?php

namespace Beromir\Ray\FusionObjects;

use Beromir\Ray\Service\RayService;
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
        $debugAction = $this->fusionValue('debugAction');
        $debugValue = $this->fusionValue('value');

        $attachedRayActions = [];

        foreach (array_keys($this->properties) as $key) {
            if (in_array($key, $this->ignoreProperties)) {
                continue;
            }

            $attachedRayActions[$key] = $this->fusionValue($key);
        }

        RayService::rayDebug($debugValue, $debugAction, $attachedRayActions);
    }
}
