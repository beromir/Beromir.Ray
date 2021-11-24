<?php

namespace Beromir\Ray\Service;

use Neos\Flow\Exception;

class RayService
{
    private static $allowedDebugActions = ['nodetypename', 'context', 'contextpath', 'properties'];

    private static $attachedRayActions = [];

    /**
     * @param $debugValue
     * @param string $debugAction
     * @param array $attachedRayActions
     * @return void
     */
    public static function rayDebug($debugValue, string $debugAction = '', array $attachedRayActions = []): void
    {
        $debugAction = strtolower($debugAction);

        self::$attachedRayActions = $attachedRayActions;

        if (is_string($debugAction) && in_array($debugAction, self::$allowedDebugActions)) {
            self::debugNodes($debugValue, $debugAction);
        } elseif (is_string($debugAction) && !empty($debugAction)) {
            ray()->once('The debug action is not known.');
        } else {
            self::debug($debugValue);
        }
    }

    /**
     * @param $debugValue
     *
     * @return void
     */
    private static function debug($debugValue): void
    {
        if (empty(self::$attachedRayActions)) {
            ray($debugValue);
        } else {
            ray(function () use ($debugValue) {

                $debug = ray($debugValue);

                foreach (self::$attachedRayActions as $key => $value) {

                    if (empty($value)) {
                        $debug = $debug->$key();
                    } else {
                        $debug = $debug->$key($value);
                    }
                }
                return $debug;
            });
        }
    }

    /**
     * @param $debugValue
     * @param string $debugAction
     *
     */
    private static function getNodeData($debugValue, string $debugAction)
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
    private static function debugNodes($debugValues, string $debugAction): void
    {
        if (is_array($debugValues)) {
            $debugArray = [];

            foreach ($debugValues as $debugValue) {
                if (is_object($debugValue) && (get_class($debugValue) === 'Neos\ContentRepository\Domain\Model\Node')) {
                    array_push($debugArray, self::getNodeData($debugValue, $debugAction));
                }
            }

            if (!empty($debugArray)) self::debug($debugArray);
        } elseif (is_object($debugValues) && (get_class($debugValues) === 'Neos\ContentRepository\Domain\Model\Node')) {
            self::debug(self::getNodeData($debugValues, $debugAction));
        }
    }
}
