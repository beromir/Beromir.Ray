<?php
declare(strict_types=1);

namespace Beromir\Ray\EelHelper;

use Beromir\Ray\Service\RayService;
use Neos\Flow\Annotations as Flow;
use Neos\Eel\ProtectedContextAwareInterface;

class RayHelper implements ProtectedContextAwareInterface
{
    /**
     * @param $debugValue
     * @param string $debugAction
     */
    public function debug($debugValue, string $debugAction = ''): void
    {
        RayService::rayDebug($debugValue, $debugAction);
    }

    /**
     * All methods are considered safe, i.e. can be executed from within Eel
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
