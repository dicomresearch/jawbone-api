<?php

namespace JawboneApi\Traits;


trait EditableTrait
{
    /**
     * Array of parameters for update record
     *
     * @return array
     */
    public function getParametersForUpdate()
    {
        $outputArray = $this->exportForJson();
        foreach ($this->paramsDisableForUpdate as $keyName) {
            unset($outputArray[$keyName]);
        }
        return $outputArray;
    }

    /**
     * Return action name part of request uri for edit exist record
     *
     * @return string
     */
    public function getEditActionName()
    {
        return 'partialUpdate';
    }
}
