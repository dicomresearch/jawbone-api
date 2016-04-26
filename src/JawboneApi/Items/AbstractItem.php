<?php

namespace JawboneApi\Items;

use JawboneApi\Interfaces\ItemInterface;
use JawboneApi\JawboneApiException;

abstract class AbstractItem implements ItemInterface
{
    const FROM_JSON_SUFFIX = 'FromJson';

    const FOR_JSON_SUFFIX = 'ForJson';

    const SETTER_PREFIX = 'set';

    const GETTER_PREFIX = 'get';

    protected $jsonToPropertyMap = [
        'xid' => 'xid'
    ];

    private $xid;

    private $isLoaded = false;

    /**
     * Populate record from array
     *
     * @param stdClass|array|string $json
     */
    public function populateFromJson($json)
    {
        $this->isLoaded = true;
        foreach ($this->jsonToPropertyMap as $jsonKey => $propertyName) {
            $transformFunctionName = $this->createSetterMethodName($propertyName, true);
            $value = $this->getValueFromJson($json, $jsonKey);
            if (!is_null($value)) {
                $this->$transformFunctionName($value);
            }
        }
    }

    /**
     * Export record to array
     *
     * @return array
     */
    public function exportForJson()
    {
        $outputArray = [];
        foreach ($this->jsonToPropertyMap as $jsonKey => $propertyName) {
            $transformFunctionName = $this->createGetterMethodName($propertyName, true);
            $value = $this->$transformFunctionName();
            if (!is_null($value)) {
                $outputArray[$jsonKey] = $this->$transformFunctionName();
            }
        }
        return $outputArray;
    }

    /**
     * @param string $xid
     */
    public function setXid($xid)
    {
        $this->xid = strval($xid);
    }

    /**
     * @return string
     */
    public function getXid()
    {
        return $this->xid;
    }

    /**
     * @return bool
     */
    public function isLoaded()
    {
        return (bool) $this->isLoaded;
    }

    /**
     * Magic implementation for the some methods
     *
     * Magic implementation for the following methods:
     * - set{PropertyName}FromJson
     * - get{PropertyName}ForJson
     * - set{PropertyName}
     * - get{PropertyName}
     *
     * @param string $methodName
     * @param array $arguments
     *
     * @return $this
     * @throws JawboneApiException
     */
    public function __call($methodName, $arguments)
    {
        if (substr($methodName, -strlen(static::FROM_JSON_SUFFIX)) == static::FROM_JSON_SUFFIX) {
            $simpleSetter = substr($methodName, 0, strlen($methodName) - strlen(static::FROM_JSON_SUFFIX));
            $this->$simpleSetter(current($arguments));
            return $this;
        } elseif (substr($methodName, -strlen(static::FOR_JSON_SUFFIX)) == static::FOR_JSON_SUFFIX) {
            $simpleGetter = substr($methodName, 0, strlen($methodName) - strlen(static::FOR_JSON_SUFFIX));
            return $this->$simpleGetter();
        } elseif (substr($methodName, 0, strlen(static::SETTER_PREFIX)) == static::SETTER_PREFIX) {
            $propertyName = lcfirst(substr($methodName, strlen(static::SETTER_PREFIX)));
            $this->$propertyName = current($arguments);
            return $this;
        } elseif (substr($methodName, 0, strlen(static::GETTER_PREFIX)) == static::GETTER_PREFIX) {
            $propertyName = lcfirst(substr($methodName, strlen(static::GETTER_PREFIX)));
            return $this->$propertyName;
        }
        throw new JawboneApiException('Method "' . $methodName . '" does not exist.');
    }

    /**
     * Create setter method name by property name
     *
     * @param $propertyName
     * @param bool $workWithJson
     * @return string
     */
    public function createSetterMethodName($propertyName, $workWithJson = false)
    {
        return static::SETTER_PREFIX . ucfirst($propertyName) . ($workWithJson ? static::FROM_JSON_SUFFIX : '');
    }

    /**
     * Create getter method name by property name
     *
     * @param $propertyName
     * @param bool $workWithJson
     * @return string
     */
    public function createGetterMethodName($propertyName, $workWithJson = false)
    {
        return static::GETTER_PREFIX . ucfirst($propertyName) . ($workWithJson ? static::FOR_JSON_SUFFIX : '');
    }

    /**
     * @param stdClass|array|string $json
     * @param string $keyName
     *
     * @return null|string
     */
    protected function getValueFromJson($json, $keyName)
    {
        $value = null;
        if (is_object($json) && property_exists($json, $keyName)) {
            $value = $json->$keyName;
        } elseif (is_array($json) && array_key_exists($keyName, $json)) {
            $value = $json[$keyName];
        } elseif (is_string($json)) {
            $value = $json;
        }
        return $value;
    }
}