<?php

namespace JawboneApi\Traits;


use JawboneApi\Interfaces\AddableInterface;

trait AddableTrait
{
    /**
     * Array of parameters for new record
     *
     * @return array
     */
    public function getParametersForCreate()
    {
        $outputArray = $this->exportForJson();
        foreach ($this->paramsDisableForCreate as $keyName) {
            unset($outputArray[$keyName]);
        }
        return $outputArray;
    }

    /**
     * Return action name part of request uri for add new record
     *
     * @return string
     */
    public function getAddActionName()
    {
        return $this->getEndPointName();
    }
}
